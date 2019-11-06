<?php

namespace App\Http\Controllers;

use App\Coupon;
use App\User;
use DateTime;
use foo\bar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Request as RequestOrigin;

class CouponController extends Controller
{

    public function registerCoupon(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'token' => 'required|unique:coupons|size:9',
            'discount_percent' => 'required|integer|max:100',
            'registered_by' => 'required',
            'expire_on' => 'required|date|after:yesterday'       //|date_format:Y-n-j
        ]);

        $date = $request->input('expire_on');
        $date_obj = DateTime::createFromFormat('Y-m-d', $date);
        $date = $date_obj->format('Y-n-j');

        if ($validator->fails()) {

            //pass validator errors as errors object for ajax response


            if (RequestOrigin::is('api*'))
                return response()->json([
                    'errors' => $validator->errors(),
                    'message' => 'Coupon Registration failed',
                    'date' => $request->input('expire_on'),
                    'status' => false]);
            return back()->with('error', $validator->errors());
        }

        $coupon = new Coupon([
            'token' => $request->input('token'),
            'discount_percent' => $request->input('discount_percent'),
            'registered_by' => $request->input('registered_by'),
            'expire_on' => $date     //date('Y-n-j', strtotime($request-> input('expire_on'))),


        ]);

        $coupon->save();

        if (RequestOrigin::is('api*'))
            return response()->json(['coupon' => $coupon, 'message' => 'Coupon registered successfully', 'status' => true], 201);

        return back()->with('msg', 'Coupon registered successful')->withInput(['msg' => 'Coupon registered ', 'coupon' => $coupon, 'status' => true]);
    }

    public function generateCoupon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'discount_percent' => 'required|max:100',
            'registered_by' => 'required',
            'token_count' => 'integer',
            'expire_on' => 'required|date|after:yesterday|'       //|date_format:Y-n-j
        ]);

        $date = $request->input('expire_on');
        $date_obj = DateTime::createFromFormat('Y-m-d', $date);
        $date = $date_obj->format('Y-n-j');

        if ($validator->fails()) {

            if (RequestOrigin::is('api*'))
                return response()->json([
                    'errors' => $validator->errors(),
                    'message' => 'Coupon Generation failed',
                    'status' => false]);
            return back()->with('error', $validator->errors());

        }

        if ($request->has('token_count'))
            $couponCount = $request->input('token_count');
        else  $couponCount = 1;

        $coupons = collect(new Coupon());

        for ($i = 1; $i <= $couponCount; $i++) {
            $token = $this->generateRandomString();

            $coupon = new Coupon([
                'token' => $token,
                'discount_percent' => $request->input('discount_percent'),
                'registered_by' => $request->input('registered_by'),
                'expire_on' => $date //date('Y-n-j', strtotime($request-> input('expire_on'))),

            ]);
            $coupons->push($coupon);
            $coupon->save();
        }

        if (RequestOrigin::is('api*'))
            return response()->json(['coupons' => $coupons, 'message' => 'Coupon generated successfully', 'status' => true], 201);

        return back()->withInput(['msg' => 'Coupons generated successfully ', 'coupons' => $coupons]);

    }

    public function generateRandomString($length = 9)
    {
        $characters = '123456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        $coupon = Coupon::withTrashed()
            ->where('token', $randomString)
            ->first();

        if ($coupon)
            return $this->generateRandomString();

        return $randomString;
    }

    public function acquireCoupon(Request $request)
    {


        //<editor-fold desc="Validate Inputs">
        if (RequestOrigin::is('api*'))
            $validator = Validator::make($request->all(), [
                'token' => 'required',
                'user_id' => 'required',
            ]);
        else
            $validator = Validator::make($request->all(), [
                'token' => 'required',
            ]);

        if ($validator->fails()) {
            if (RequestOrigin::is('api*'))
                return response()->json([
                    'errors' => $validator->errors(),
                    'message' => 'Operation failed',
                    'status' => false], 400);
            return back()->with('error', $validator->errors());
        }

        //</editor-fold>

        //Assign values

        $token = $request->input('token');
        if (RequestOrigin::is('api*')) {
            $userId = $request->input('user_id');

        } else $userId = Auth::user()->id;

        $user = User::find($userId);

        if (!$user) {
            if (RequestOrigin::is('api*'))
                return response()->json(['message' => 'User Not found'], 404);

            return back()->with('error', 'Not authorized');
        }

        //<editor-fold desc="Check if user have something to purchase">
        $cart = $user->cartPieces()->get();  //Piece::where('purchased_by', $userId)->get();

        $piecesCount = $cart->count();
        if ($piecesCount < 1)
            return response()->json(['message' => 'Nothing to purchase, cart is empty'], 403);
        //</editor-fold>


        //<editor-fold desc="Validate Coupon token">
        $coupon = Coupon::all()
            ->where('token', $token)
            ->first();

        // Is coupon exists ?
        if (!$coupon) {
            if (RequestOrigin::is('api*'))
                return response()->json([
                    'message' => 'Invalid Token',
                    'status' => false], 404);
            return back()->with('error', 'Invalid Token ');
        }


        // Is coupon used ?
        if ($coupon->user_id) {

            if (RequestOrigin::is('api*'))
                return response()->json([
                    'message' => 'This coupon already acquired',
                    'status' => false], 404);
            return back()->with('error', 'Coupon already acquired');
        }


        // Is coupon expired ?
        if ($coupon->expire_on < today()) {
            if (RequestOrigin::is('api*'))
                return response()->json([
                    'message' => 'Sorry, this coupon Expired since ' . $coupon->expire_on,
                    'status' => false], 404);
            return back()->with('error', 'Sorry, this coupon Expired since ' . $coupon->expire_on );
        }

        //</editor-fold>


        /*
         * Assign the token to the user
         */
        //$coupon->user_id = $userId;

        $coupon->update([
            'acquired_by' => $userId
        ]);

        if (RequestOrigin::is('api*'))
            return response()->json([
                'message' => 'Congratulations!!, discount coupon added, you have ' . $coupon->discount_percent . '% discount, use it before ' . $coupon->expire_on,
                'status' => false], 202);

        return back()->with('msg', 'Congratulations, discount coupon added, you have ' . $coupon->discount_percent . '% discount, use it before ' . $coupon->expire_on);

    }

//    /**
//     * Display a listing of the resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function index()
//    {
//        //
//    }
//
//    /**
//     * Show the form for creating a new resource.
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function create()
//    {
//        //
//    }
//
//    /**
//     * Store a newly created resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return \Illuminate\Http\Response
//     */
//    public function store(Request $request)
//    {
//        //
//    }
//
//    /**
//     * Display the specified resource.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function show($id)
//    {
//        //
//    }
//
//    /**
//     * Show the form for editing the specified resource.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function edit($id)
//    {
//        //
//    }
//
//    /**
//     * Update the specified resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function update(Request $request, $id)
//    {
//        //
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function destroy($id)
//    {
//        //
//    }
}
