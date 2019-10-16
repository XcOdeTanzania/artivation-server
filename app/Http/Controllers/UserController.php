<?php

namespace App\Http\Controllers;


use App\User;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\JWTAuth;

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
            'photo_url' => $site_url.$request->input('photo_url'),
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
        
        if($request->input('phone'))
        $phoneValidator = $this->isPhoneValid($request->input('phone'));
        
        if($request->input('phone') && $phoneValidator['status']!= true){  
            return response()->json([
                'message' => 'Phone Not  Valid',
                'status' => false
            ]);
        }
       
        
        $site_url = "http://www.artivation.co.tz/backend/api/piece/image/";
        $user->update([
            'username' => $request->input('username') ? $request->input('username') : $user->username ,
            'photo_url' => $site_url.$request->input('photo_url'),
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

    function deleteUser($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'User Not found']);
        }
        $user->delete();
        return response()->json(['message' => 'The user has been deleted'], 200);
    }
    
    function isPhoneValid($phone){
        
        if(preg_match('/^\+[0-9]{12}$/',$phone)){
           // return true;
         return array(
                'message' => 'Valid phone number',
                'status' => true)
           ; 
          
        }
        
         if(preg_match('/^0[0-9]{9}$/',$phone)){
            
            return array(
                'message' => 'Valid phone number',
                'status' => true
            ); 
        }
        
        if(preg_match('/\+?([0-9]{2})-?([0-9]{3})-?([0-9]{6,7})/',$phone)){
          return array(
                'message' => 'Unsupported phone number',
                'status' => false
            ); 
        }
        
        else  return array(
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
}
