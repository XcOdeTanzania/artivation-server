<?php

namespace App\Http\Controllers;

use App\Coupon;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Libraries\OAuth2\OAuthSignatureMethod_HMAC_SHA1;
use App\Libraries\OAuth2\OAuthConsumer;
use App\Libraries\OAuth2\OAuthRequest;

use App\Piece;
use App\User;

class PesaPalController extends Controller
{

    private $token;
    private $params;
    private $consumer_key;
    private $consumer_secret;
    private $signature_method;
    private $iframelink;
    private $callback_url;

//    private $tax_value;
//    private $shiping_cost_per_item;

    private $consumer;

    public function __construct()
    {
        $this->token = $this->params = NULL;
        $this->consumer_key = 'yzHhVWWFXpYAumXkhcTYqR6WEIm796+/';
        $this->consumer_secret = 'j9RjWxdOqRmQLOKfQJyXFqxKjg4=';
        $this->signature_method = new OAuthSignatureMethod_HMAC_SHA1();

        //$iframelink = 'http://demo.pesapal.com/api/PostPesapalDirectOrderV4';
        $this->iframelink = 'https://www.pesapal.com/API/PostPesapalDirectOrderV4';

        $this->consumer = new OAuthConsumer($this->consumer_key, $this->consumer_secret);

        $this->callback_url = 'https://www.artivation.co.tz/backend/api/pesapalCallback';

//        $this->shiping_cost_per_item = 7;
//        $this->tax_value = 0.06;
    }

    function getPesaPalIframeUrl($userId)
    {
        // $iframe_src = parse_url($this->getIframeSrc($userId));
        $iframe_src = $this->getIframeSrc($userId);


        echo $iframe_src;
        // return  response()->json(['url'=>$iframe_src],200);
        //return redirect($iframe_src) ;
    }

    function getPesaPalIframe($userId)
    {

        //$_POST = json_decode(file_get_contents('php://input'), true);

        $iframe_src = $this->getIframeSrc($userId);

        return '<iframe src="' . $iframe_src . '" width="100%" height="1000px" scrolling="auto" frameBorder="0"> <p>Unable to load the payment page</p> </iframe>';
    }

    function redirectToPesapal($userId)
    {
        //header("https://artivation.co.tz/backend/api/pesapalIframe/".$userId);
        return redirect($this->getIframeSrc($userId, site_url . '/payment/status'));
    }

    function getIframeSrc($userId, $callback = null)
    {
        $user = User::find($userId);
        if (!$user)
            return response()->json(['message' => 'User not found'], 404);

        //$cart = Piece::where('purchased_by', $userId)->get();
        $cart = $user->cart();

        $piecesCount = $cart->count();

        if ($piecesCount < 1)
            return response()->json(['message' => 'Nothing to purchase, cart is empty'], 403);


        //$piecesIds = $cart->modelKeys();
        $pieces = $user->cartPieces()->get();  //$cart->pieces();
        $piecesIds = $pieces->modelKeys();

        $cartSubtotal = 0;
        foreach ($pieces as $piece) {
            $cartSubtotal = $cartSubtotal + $piece->price;
        }

        $tax = $cartSubtotal * tax_value;

        $shippingCost = $piecesCount * shipping_cost_per_item;

        $totalAmount = $cartSubtotal + $tax + $shippingCost;

        $coupon = $user->coupon()->first();
        $discountPercent = 0;

        if ($coupon && $coupon->expire_on >= today() && $coupon->used_at == null)
            $discountPercent = $coupon->discount_percent;
        $discountAmount = $totalAmount * ($discountPercent / 100);

        $amount = $totalAmount - $discountAmount;

        $transactionIndex = NULL;

        $last_transaction = DB::table('transactions')
            ->latest()
            ->first();
        if (!$last_transaction) {
            $transactionIndex = 1;
        } else {
            $transactionIndex = $last_transaction->id + 1;
        }

        $transactionReference = str_pad($transactionIndex, 4, '0', STR_PAD_LEFT) .
            '_' . str_pad($user->id, 4, '0', STR_PAD_LEFT) . '_' . time();

        // Save the transaction

        $transaction = new Transaction([
            'amount' => $amount,
            'status' => 'PLACED',
            'tracking_id' => '',
            'reference_no' => $transactionReference,
            'payment_method' => '',
            'items_purchased' => serialize($piecesIds),
            'tax' => $tax,
            'shipping_cost' => $shippingCost,
            'discount' => $discountAmount,
            'coupon_id' => $coupon ? $coupon->id : null,
        ]);

        $user->transactions()->save($transaction);

        //$amount = $payableAmount;
        if ($piecesCount > 1)
            $desc = 'Purchase of ' . $piecesCount . ' Art pieces';
        else
            $desc = 'Purchase of an Art piece';
        $type = 'MERCHANT';
        $reference = $transactionReference;
        $first_name = '';
        $last_name = '';
        $email = $user->email;
        $phonenumber = $user->phone;


        $amount = number_format($amount, 2);


        $lineItem = "<LineItems>";
        $itm = 1;
        foreach ($cart as $piece)
            $lineItem = $lineItem . "
                                    <LineItem 
                                        UniqueId=\"" . $piece->id . "\" 
                                        Particulars=\"" . $piece->title . "\" 
                                        Quantity=\"" . $itm . "\" 
                                        UnitCost=\"" . $piece->price . "\" 
                                        SubTotal=\"" . $piece->price . "\" />";
        $lineItem = $lineItem . "
                                    <LineItem 
                                        UniqueId=\"tax\" 
                                        Particulars=\"Tax\" 
                                        Quantity=\"1\" 
                                        UnitCost=\"" . $tax . "\" 
                                        SubTotal=\"" . $tax . "\" />";
        $lineItem = $lineItem . "
                                    <LineItem 
                                        UniqueId=\"ship\" 
                                        Particulars=\"Shipping Cost\" 
                                        Quantity=\"1\" 
                                        UnitCost=\"" . $shippingCost . "\" 
                                        SubTotal=\"" . $shippingCost . "\" />";
        $lineItem = $lineItem . "
                                </LineItems>";


        $post_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?>
				<PesapalDirectOrderInfo 
				xmlns:xsi=\"http://www.w3.org/2001/XMLSchemainstance\" 
				xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" 
				Amount=\"" . $amount . "\" 
				Description=\"" . $desc . "\" 
				Type=\"" . $type . "\" 
				Reference=\"" . $reference . "\" 
				FirstName=\"" . $first_name . "\" 
				LastName=\"" . $last_name . "\" 
				Email=\"" . $email . "\" 
				PhoneNumber=\"" . $phonenumber . "\"
				xmlns=\"http://www.pesapal.com\">
				</PesapalDirectOrderInfo>";

        $post_xml = htmlentities($post_xml);


//post transaction to pesapal
        $iframe_src = OAuthRequest::from_consumer_and_token($this->consumer, $this->token, "GET",
            $this->iframelink, $this->params);
        $iframe_src->set_parameter("oauth_callback", $callback ?: $this->callback_url);
        $iframe_src->set_parameter("pesapal_request_data", $post_xml);
        $iframe_src->sign_request($this->signature_method, $this->consumer, $this->token);


        //echo $transactionReference;
        //  return redirect($iframe_src) ;
        return $iframe_src;

    }


    /*
     * If you update this method @callbackHandler
     * please make same changes to @callbackHandlerWeb
     */
    function callbackHandler()
    {
        $trackingId = Input::get('pesapal_transaction_tracking_id');
        $referenceNo = Input::get('pesapal_merchant_reference');

        $transactionStatus = (array)json_decode($this->checkTransactionStatus($referenceNo, $trackingId), true);

        // Save the transaction status
        $transaction = Transaction::where('reference_no', $referenceNo)->first();

        if (!$transaction)
            return response()->json([
                'message' => 'Transaction not found',
                'status' => false
            ]);

        if($transaction->coupon_id){
            $coupon = Coupon::find($transaction->coupon_id);
            $coupon -> update([
                'used_at' => Carbon::now()->toDateTimeString()
            ]);
        }


        $paymentStatus = $transactionStatus ['status'];

        $transaction->update([
            'status' => $transactionStatus['status'],
            'tracking_id' => $transactionStatus['pesapal_transaction_tracking_id'],
            'payment_method' => $transactionStatus['payment_method'],
        ]);


        if ($paymentStatus == 'PENDING') {
            return response()->json(['message' => 'Your purchase is pending, please complete the payment', 'status' => $paymentStatus], 201);
        }
        if ($paymentStatus == 'COMPLETED') {

            $pieces = Piece::find(unserialize($transaction->items_purchased));
            foreach ($pieces as $piece) {
                $piece->update([
                    'cart_status' => false,
                    'purchased_by' => $transaction->user_id
                ]);
            }
            return response()->json(['message' => 'Thank you, your purchase is complete', 'status' => $paymentStatus, 'items' => Piece::find(unserialize($transaction->items_purchased))], 200);
        }
        if ($paymentStatus == 'FAILED') {
            return response()->json(['message' => 'Sorry, your purchase failed', 'status' => $paymentStatus], 412);
        }
        if ($paymentStatus == 'INVALID>') {
            return response()->json(['message' => 'Something went wrong', 'status' => $paymentStatus], 422);
        }


    }

    function callbackHandlerWeb()
    {
        $trackingId = Input::get('pesapal_transaction_tracking_id');
        $referenceNo = Input::get('pesapal_merchant_reference');

        $transactionStatus = (array)json_decode($this->checkTransactionStatus($referenceNo, $trackingId), true);

        // Save the transaction status
        $transaction = Transaction::where('reference_no', $referenceNo)->first();

        if (!$transaction)
            return view('payment_callback', ['header' => 'Sorry !!', 'message' => 'Sorry Transaction not found', 'status' => '']);

        if($transaction->coupon_id != null){

            $coupon = Coupon::find($transaction->coupon_id);
            $coupon -> update([
                'used_at' => Carbon::now()->toDateTimeString()
            ]);
            //$coupon->used_at = Carbon::now()->toDateTimeString();
        }

        $paymentStatus = $transactionStatus ['status'];

        $transaction->update([
            'status' => $transactionStatus['status'],
            'tracking_id' => $transactionStatus['pesapal_transaction_tracking_id'],
            'payment_method' => $transactionStatus['payment_method'],
        ]);


        if ($paymentStatus == 'PENDING') {
            return view('payment_callback', ['time'=>Carbon::now()->toDateTimeString(),'header' => 'Thank You !!', 'message' => 'Your purchase is pending, please complete the payment', 'status' => $paymentStatus, 'transaction' => $transaction, 'items' => Piece::find(unserialize($transaction->items_purchased))]);
        }
        if ($paymentStatus == 'COMPLETED') {

            $pieces = Piece::find(unserialize($transaction->items_purchased));
            foreach ($pieces as $piece) {
                $piece->update([
                    'cart_status' => false,
                    'purchased_by' => $transaction->user_id
                ]);
            }
            return view('payment_callback', ['header' => 'Thank You !!!', 'message' => 'Your purchase is complete, will contact you soon for the delivery', 'status' => $paymentStatus, 'items' => Piece::find(unserialize($transaction->items_purchased)), 'transaction' => $transaction]);
        }
        if ($paymentStatus == 'FAILED') {
            return view('payment_callback', ['header' => 'Sorry !!', 'message' => 'Your purchase failed, please try again, make sure you have sufficient balance', 'status' => $paymentStatus, 'transaction' => $transaction, 'items' => Piece::find(unserialize($transaction->items_purchased))]);
        }
        if ($paymentStatus == 'INVALID>') {
            return view('payment_callback', ['header' => 'Ooh No !!', 'message' => 'Something went wrong', 'status' => $paymentStatus]);
        }


    }

    function checkTransactionStatus($referenceNo, $trackingId)
    {

        $consumer = new OAuthConsumer($this->consumer_key, $this->consumer_secret);

        $api = 'https://www.pesapal.com';

        $QueryPaymentStatus = $api . '/API/QueryPaymentStatus';

        $QueryPaymentStatusByMerchantRef = $api . '/API/QueryPaymentStatusByMerchantRef';

        $querypaymentdetails = $api . '/API/querypaymentdetails';

        // Request Status

        $request_status = OAuthRequest::from_consumer_and_token(
            $consumer,
            $this->token,
            "GET",
            $querypaymentdetails,
            $this->params
        );
        $request_status->set_parameter("pesapal_merchant_reference", $referenceNo);
        $request_status->set_parameter("pesapal_transaction_tracking_id", $trackingId);
        $request_status->sign_request($this->signature_method, $consumer, $this->token);

        $responseData = $this->curlRequest($request_status);

        $pesapalResponse = explode(",", $responseData);
        $pesapalResponseArray = array('pesapal_transaction_tracking_id' => $pesapalResponse[0],
            'payment_method' => $pesapalResponse[1],
            'status' => $pesapalResponse[2],
            'pesapal_merchant_reference' => $pesapalResponse[3]);
        //echo json_encode($pesapalResponseArray);
        return json_encode($pesapalResponseArray);


    }

    function curlRequest($request_status)
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $request_status);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        if (defined('CURL_PROXY_REQUIRED')) if (CURL_PROXY_REQUIRED == 'True') {
            $proxy_tunnel_flag = (
                defined('CURL_PROXY_TUNNEL_FLAG')
                && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE'
            ) ? false : true;
            curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
            curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
        }

        $response = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $raw_header = substr($response, 0, $header_size - 4);
        $headerArray = explode("\r\n\r\n", $raw_header);
        $header = $headerArray[count($headerArray) - 1];

        //transaction status
        $elements = preg_split("/=/", substr($response, $header_size));
        $pesapal_response_data = $elements[1];

        return $pesapal_response_data;
    }

    function getUserTransactions($userId)
    {

        $user = User::find($userId);
        if (!$user)
            return response()->json(['message' => 'User not found'], 404);

        $transactions = Transaction::where('user_id', $userId)->where('status', '!=', 'PLACED')->get();
        //makeVisible($transactions,'');
        foreach ($transactions as $transaction)
            $transaction->items = Piece::find(unserialize($transaction->items_purchased));
        return response()->json(['transactions' => $transactions], 200);
    }

    function getUserPurchases($userId)
    {

        $user = User::find($userId);
        if (!$user)
            return response()->json(['message' => 'User not found'], 404);

        $transactions = Transaction::where('user_id', $userId)->where('status', '=', 'PENDING')->get();
        $purchased = collect();
        foreach ($transactions as $transaction) {
            $transaction->items = Piece::find(unserialize($transaction->items_purchased));
            $purchased = $purchased->toBase()->merge(Piece::find(unserialize($transaction->items_purchased)));
        }

        return response()->json(['Purchases' => $purchased], 200, [], JSON_NUMERIC_CHECK);
    }
}
