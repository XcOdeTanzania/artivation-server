<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//users end-points
Route::get('users', ['uses' => 'UserController@getAllUsers']);
Route::post('signUp', ['uses' => 'UserController@postUser']);
Route::post('login', ['uses' => 'UserController@login']);
Route::put('user/{userId}', ['uses' => 'UserController@putUser']);
Route::delete('user/{userId}', ['uses' => 'UserController@deleteUser']);
Route::put('update/profile/{userId}', ['uses' => 'UserController@updateUser']);
Route::put('resetPassword', ['uses' => 'Auth\ResetPasswordController@resetPasswordUsingCode']);
Route::post('forgotPassword', ['uses' => 'Auth\ForgotPasswordController@appSendResetLinkEmail']);


//category end-points
Route::get('categories', ['uses' => 'CategoryController@getCategories']);
Route::post('category', ['uses' => 'CategoryController@postCategory']);
Route::get('category/{categoryId}', ['uses' => 'CategoryController@getCategory']);
Route::get('category/pieces/{categoryId}/{userId}', ['uses' => 'CategoryController@getCategoryPieces']);
Route::put('category/{categoryId}', ['uses' => 'CategoryController@putCategory']);
Route::delete('category/{categoryId}', ['uses' => 'CategoryController@deleteCategory']);

//artist end-points
Route::get('artists', ['uses' => 'ArtistController@getArtists']);
Route::post('artist', ['uses' => 'ArtistController@postArtist']);
Route::get('artist/{artistId}', ['uses' => 'ArtistController@getArtist']);
Route::put('artist/{artistId}', ['uses' => 'ArtistController@putArtist']);
Route::delete('artist/{artistId}', ['uses' => 'ArtistController@deleteArtist']);


//piece end-points
Route::get('piece', ['uses' => 'PieceController@getPiece']);
Route::post('piece', ['uses' => 'PieceController@postPiece']);
Route::post('piece/cart', ['uses' => 'PieceController@addPieceToCart']);
Route::get('pieces/{user_id}', ['uses' => 'PieceController@getPieces']);
Route::get('piece/{pieceId}', ['uses' => 'PieceController@getPiece']);
Route::put('piece/{pieceId}', ['uses' => 'PieceController@putPiece']);
Route::delete('piece/{pieceId}', ['uses' => 'PieceController@deletePiece']);
Route::get('piece/image/{pieceId}',['uses'=>'PieceController@viewPiece']);
Route::get('pieces/purchased/{userId}', ['uses' => 'PieceController@getPurchasedPieces']);



//Like end-points
Route::post('like', ['uses'=>'LikeController@postLike']);
Route::get('like/{image_id}',['uses'=>'LikeController@getUsersWhoLikedPiece']);
Route::get('like/{image_id}/{user_id}',['uses'=>'LikeController@isLikedByMe']);

// PesaPal Transaction end-points
Route::get('pesapalIframe/{user_id}',['uses'=>'PesaPalController@getPesaPalIframe']);

Route::get('pesapalRedirect/{user_id}',['uses'=>'PesaPalController@getPesaPalIframeUrl']);
Route::get('pesapalUrl/{user_id}',['uses'=>'PesaPalController@getPesaPalIframeUrl']);
Route::get('userTransactions/{user_id}',['uses'=>'PesaPalController@getUserTransactions']);
Route::get('transactions/{user_id}',['uses'=>'PesaPalController@getUserTransactions']);

Route::get('purchases/{user_id}',['uses'=>'PesaPalController@getUserPurchases']);

Route::get('pesapalCallback',['uses'=>'PesaPalController@callbackHandler']);


