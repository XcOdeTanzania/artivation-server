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
        $site_url = "http://192.168.1.107:8000/api/piece/image/";
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

    function deleteUser($userId)
    {
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['message' => 'User Not found']);
        }
        $user->delete();
        return response()->json(['message' => 'The user has been deleted'], 200);
    }
}
