<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\JWTAuth;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
        $this->middleware('guest');
    }

//    public function __construct()
//    {
//        $this->middleware('guest');
//    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => decrypt($request->email)]
        );
    }

    public function resetPasswordUsingCode(Request $request)
    {
//        DB::table('password_resets')
//            ->where('email', $notifiable->email)
//            ->update(['code' => $code]);

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' =>'required',
            'code' =>'required'
        ]);

        if ($validator->fails())
            return response()->json([
                'errors' => $validator->errors(),
                'message' => $validator->errors()->first(),
                'status' => false],400);


        $passwordReset = DB::table('password_resets')->where('email', $request->input('email'))->first();
        if(!$passwordReset)
            return  response()->json([
        'message' => 'Email not found or code expired, please request password reset again',
        'status' => false
    ],404);;
        if($passwordReset->code != $request->input('code') )
            return response()->json([
                'message' => 'Invalid code, please check the code carefully or request new one, ',
                'status' => false
            ],403);

        $user = User::where('email', $passwordReset->email)->first();
        if(!$user)
            return response()->json([
                'message' => 'Something went wrong, we couldn\'t find any user with specified email' ,
                'status' => false
            ],404);
        $password = $request->input('password');
        $this->resetPassword($user,$password);

        if (!$token = $this->jwt->attempt($request->only('email', 'password'))) {
            return response()->json(['errors' => [
                'Error' => 'Password reset failed',
                'status' => false
            ]],400);
        }

        $user ->token = $token;
        return response()->json([
            'user' => $user,
            'message' => 'Password Reset Successfully' ,
            'status' => true
        ],200);
    }

//    public function resetPassword($user, $password)
//    {
//        $user->password = Hash::make($password);
//
//        $user->setRememberToken(Str::random(60));
//
//        $user->save();
//
//        event(new PasswordReset($user));
//
//        $this->guard()->login($user);
//    }
}
