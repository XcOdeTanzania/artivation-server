<?php

namespace App\Http\Controllers;


use App\Artist;
use App\Cart;
use App\Piece;
use App\Role;
use App\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;


class UserController extends Controller
{
    protected $jwt;


    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;

    }

    function getAllUsers()
    {
        return response()->json([
            'users' => User::all()
        ]);
    }


    function getUser($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'status' => false
            ]);
        }
        return response()->json([
            'user' => $user,
            'status' => false
        ]);
    }

    function postUser(Request $request)
    {
        // $site_url = "http://192.168.1.107:8000/api/piece/image/";
        $site_url = "http://www.artivation.co.tz/backend/api/piece/image/";
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',

        ]);

        if ($validator->fails()) {


            //pass validator errors as errors object for ajax response

            return response()->json([
                'errors' => $validator->errors(),
                'status' => false]);
        }

        $user = new User([
            'username' => $request->input('username'),
            'photo_url' => $site_url . $request->input('photo_url'),
            'email' => $request->input('email'),
            'sex' => $request->input('sex'),
            'phone' => $request->input('phone'),
            'password' => app('hash')->make($request->input('password')),
        ]);

        $user->save();


        try {
            if (!$token = $this->jwt->attempt($request->only('email', 'password'))) {
                return response()->json(['errors' => [
                    'user' => 'User Not found! Please signup',
                    'status' => false
                ]]);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent' => $e->getMessage()], $e->getStatusCode());
        }

        return response()->json(array(
            'token' => $token,
            'username' => Auth::user()->username,
            'photo_url' => Auth::user()->photo_url,
            'email' => Auth::user()->email,
            'sex' => Auth::user()->sex,
            'phone' => Auth::user()->phone,
            'id' => Auth::user()->id,
            'status' => true));


    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {

            //pass validator errors as errors object for ajax response

            return response()->json([
                'errors' => $validator->errors(),
                'status' => false]);
        }

        try {
            if (!$token = $this->jwt->attempt($request->only('email', 'password'))) {
                return response()->json(['errors' => [
                    'user' => 'User Not found! Please signup',
                    'status' => false
                ]]);
            }
        } catch (TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (JWTException $e) {
            return response()->json(['token_absent' => $e->getMessage()], $e->getStatusCode());
        }

        return response()->json(array(
            'token' => $token,
            'username' => Auth::user()->username,
            'photo_url' => Auth::user()->photo_url,
            'email' => Auth::user()->email,
            'sex' => Auth::user()->sex,
            'phone' => Auth::user()->phone,
            'id' => Auth::user()->id,
            'status' => true));
    }

    public function assignRole(Request $request)
    {

        if (!Auth::user() || Auth::user()->role()->name != 'Administrator')
            return back()->with('error', 'Not authorized');

        $this->validate($request, [
            'email' => 'required',
            'role_id' => 'required'
        ]);

        $user = User::whereEmail($request->input('email'))->first();

        if (!$user)
            return back()->with('error', 'Couldn\'t find user with that email');

        $role = Role::find($request->input('role_id'));

        if (!$role)
            return back()->with('error', 'Couldn\'t find the role selected');

//        $user->update([
//            'role_id' => $request->input('role_id')
//        ]);

        if ($user->role()->name === 'Artist') {

        }

        // Prevent None administrator system
        if ($user->role()->name === 'Administrator' && $user->role()->users()->get()->count() <= 1)
            return back()->with('error', 'System should have at least one administrator ');

        // Prevent None administrator system
        if ($user->role()->name === 'Artist' ){
            $artist = Artist::whereUserId($user->id)->first();

            if($artist && $artist->pieces()->get()->count()>= 1)
                return back()->with('error', 'Artist is associated with one or more art piece, can\'t be changed');
        }





            $user->role_id = $role->id;
            $user->update();

            if ($role->name === 'Artist') {
                $artist = Artist::whereUserId($user->id)->first();
                if (!$artist) {
                    $artist = new Artist([
                        'user_id' => $user->id,
                        'number_of_pieces' => 0,
                        'number_of_pieces_bought' => 0,
                        'number_of_likes' => 0,
                        'ratings' => 1,
                        'folder' => $user->username
                    ]);
                    $artist->save();
                }

            } else {
                $artist = Artist::whereUserId($user->id)->first();
                if ($artist)
                    $artist->destroy();
            }
            return back()->with('msg', $user->username . ' is Now ' . $role->name);
        }

    function putUser(Request $request, $userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'message' => 'User Not found',
                'status' => false
            ]);
        }

        $user->update([
            'username' => $request->input('username'),
            'photo_url' => $request->input('photo_url'),
            'email' => $request->input('email'),
        ]);

        return response()->json([
            'user' => $user,
            'status' => true
        ]);
    }

    function updateUser(Request $request, $userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'message' => 'User Not found',
                'status' => false
            ]);
        }

        if ($request->input('phone'))
            $phoneValidator = $this->isPhoneValid($request->input('phone'));

        if ($request->input('phone') && $phoneValidator['status'] != true) {
            return response()->json([
                'message' => 'Phone Not  Valid',
                'status' => false
            ]);
        }


        $site_url = "http://www.artivation.co.tz/backend/api/piece/image/";
        $user->update([
            'username' => $request->input('username') ? $request->input('username') : $user->username,
            'photo_url' => $site_url . $request->input('photo_url'),
            'email' => !$request->input('email') ? $user->email : $request->input('email'),
            'sex' => !$request->input('sex') ? $user->sex : $request->input('sex'),
            'phone' => !$request->input('phone') ? $user->phone : $request->input('phone'),
        ]);

        return response()->json([
            'user' => $user,
            'message' => 'Updated successfuly',
            'status' => true
        ]);
    }

    function checkOutUser($userId = null)
    {
        if (!$userId)
            $userId = Auth::user()->id;

        $user = User::find($userId);
        if (!$user)
            return response()->json(['message' => 'User not found'], 404);

        //$cart = Cart::where('user_id', $userId)->get();
        $cart = $user->cart()->get();


        $piecesCount = $cart->count();

        if ($piecesCount < 1)
            return response()->json(['message' => 'Nothing to purchase, cart is empty'], 403);

        $cartSubtotal = 0;

        $pieces = $user->cartPieces()->get();
        foreach ($pieces as $piece) {
            $piece['image'] = site_url . $piece->image;
            $cartSubtotal = $cartSubtotal + $piece->price;
        }

        $tax = $cartSubtotal * tax_value;

        $shippingCost = $piecesCount * shipping_cost_per_item;

        $totalAmount = $cartSubtotal + $tax + $shippingCost;
        $coupon = $user->coupon()->first();
        $discountPercent = 0;

        if ($coupon && $coupon->expire_on >= today())
            $discountPercent = $coupon->discount_percent;

        //= $user->coupon()->first()->discount_percent;

        $discountAmount = $totalAmount * ($discountPercent / 100);
        $discountAmount = round($discountAmount, 0);


        $amount = $totalAmount - $discountAmount;

        if (\Illuminate\Support\Facades\Request::is('api*'))
            return response()->json([
                'cart' => $cart,
                'pieces' => $pieces,
                'tax' => $tax,
                'sub_total' => $cartSubtotal,
                'shipping' => $shippingCost,
                'total_amount' => $totalAmount,
                'discount_percent' => $discountPercent,
                'discount' => $discountAmount,
                'payable' => $amount

            ], 200, [], JSON_NUMERIC_CHECK);

        return view('cart',
            [
                'pieces' => $pieces,
                'tax' => $tax,
                'shipping' => $shippingCost,
                'subTotal' => $cartSubtotal,
                'total' => $totalAmount,
                'discount' => $discountAmount,
                'payable' => $amount
            ]);

    }

    function deleteUser($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'User Not found']);
        }
        $user->delete();
        return response()->json(['message' => 'The user has been deleted'], 200);
    }

    function isPhoneValid($phone)
    {

        if (preg_match('/^\+[0-9]{12}$/', $phone)) {
            // return true;
            return array(
                'message' => 'Valid phone number',
                'status' => true);

        }

        if (preg_match('/^0[0-9]{9}$/', $phone)) {

            return array(
                'message' => 'Valid phone number',
                'status' => true
            );
        }

        if (preg_match('/\+?([0-9]{2})-?([0-9]{3})-?([0-9]{6,7})/', $phone)) {
            return array(
                'message' => 'Unsupported phone number',
                'status' => false
            );
        } else  return array(
            'message' => 'Invalid phone number',
            'status' => false
        );

        // if(preg_match("/^[0-9]{3}-[0-9]{4}-[0-9]{4}$/", $phone)) {
        //     return false;
        //     // return response()->json([
        //     //     'message' => 'Wrong phone number',
        //     //     'status' => false
        //     // ]); 
        //   }
    }

    public function updateUserProfile(Request $request)
    {

        $user = Auth::user();
        if (!$user)
            return back()->with('error', 'Not Authenticated');

        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'phone' => 'required',
            'sex' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        $user->username = $request->input('username');
        $user->phone = $request->input('phone');
        $user->sex = $request->input('sex');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('profiles');
            $user->photo_url = site_url . $path;
        }

        $user->update();

        return back()->with('msg', 'Profile Updated')->withInput(['msg' => 'profile updated', 'user' => $user]);


    }

    public function changeEmail(Request $request)
    {

        $user = Auth::user();
        if (!$user)
            return back()->with('error', 'Not Authenticated');

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if ($request->input('email') === $user->email)
            return back();

        if ($validator->fails()) {
            return back()->with('error', $validator->errors());
        }

        if (Hash::check($request->input('password'), $user->password)) {
            $user->email = $request->input('email');
            $user->update();
            return back()->with('msg', 'Email changed')->withInput(['msg' => 'Profile Updated', 'user' => $user]);
        } else
            return back()->with('error', 'Not Authorized');


    }

    public function changePassword(Request $request)
    {

        $user = Auth::user();
        if (!$user)
            return back()->with('error', 'Not Authenticated');

        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required',
            'confirm_new_password' => 'required',

        ]);

        if ($validator->fails())
            return back()->with('error', $validator->errors());

        if ($request->input('new_password') != $request->input('confirm_new_password'))
            return back()->with('error', 'Password mismatch');

        if (Hash::check($request->input('current_password'), $user->password)) {
            $user->password = bcrypt($request->input('new_password'));
            $user->update();
            return back()->with('msg', 'Password changed')->withInput(['msg' => 'Password Updated', 'user' => $user]);
        } else
            return back()->with('error', 'Not Authorized');

    }
}
