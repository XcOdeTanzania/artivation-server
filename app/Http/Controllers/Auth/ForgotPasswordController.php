<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return back()->withErrors(
            ['email' => trans($response)]
        )->withInput($request->only('email'));
    }

    public function appSendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        $user =  DB::table('users')->where('email', $request->input('email'))->first();
        if(!$user)
        return response()->json([
            'message' => 'Email not found, please register' ,
            'status' => false
        ],404);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );

        if($response != Password::RESET_LINK_SENT)
            return response()->json([
                'message' => 'Something went wrong, we couldn\'t send reset code' ,
                'status' => false
            ],400);

        $this->sendResetLinkResponse($request, $response);

        return response()->json([
            'message' => 'We have sent a code to reset your email' ,
            'status' => true
        ],201);

    }
}
