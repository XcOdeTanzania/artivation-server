<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//
//    return view('welcome',['uses'=>'PieceController@showAllPosts' ,'as' => 'welcome']);
//});


/// Authentication Routes

//<editor-fold desc="User Routes">
Auth::routes();

Route::get('/password/reset/{token}/{email}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

Route::get('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    return redirect('/');
});

Route::get('/user/profile', function () {
    return view('user_profile');
})->middleware('auth')->name('user.profile');

Route::get('user/update', function () {
    return view('user_update');
})->middleware('auth')->name('user.update');

Route::get('user/credentials', function () {
    return view('user_credentials');
})->middleware('auth')->name('user.credentials');

Route::post('profile/update', ['uses' => 'UserController@updateUserProfile'])->middleware('auth')->name('update.profile');
Route::post('email/update', ['uses' => 'UserController@changeEmail'])->middleware('auth')->name('update.email');
Route::post('password/update', ['uses' => 'UserController@changePassword'])->middleware('auth')->name('update.password');
//</editor-fold>

//<editor-fold desc="Admin Routes">
Route::get('/admin/dashboard', function () {
    $roles = \App\Role::all();
    return view('admin_dashboard', ['roles' => $roles]);
})->middleware('role:Administrator')->name('admin.dashboard');
Route::put('/user/role', ['uses' => 'UserController@assignRole'])->middleware(['auth', 'role:Administrator']);

Route::get('/coupon/management', function () {
    return view('coupon_management');
})->middleware(['auth','role:Administrator'])->name('coupon.management');
Route::get('/coupon/view', function () {
    $coupons = \App\Coupon::all();
    return view('admin_coupons', ['coupons' => $coupons]);
})->middleware(['auth','role:Administrator'])->name('admin.coupons');
Route::get('/admin/user', function () {
    $users = \App\User::all();
    return view('admin_users', ['users' => $users]);
})->middleware(['auth','role:Administrator'])->name('admin.users');

Route::POST('/coupons/generate',['uses'=>'CouponController@generateCoupon'])->middleware(['auth','role:Administrator'])->name('coupon.generate');
Route::POST('/coupons/register',['uses'=>'CouponController@registerCoupon'])->middleware(['auth','role:Administrator'])->name('coupon.register');
//</editor-fold>


/// Artist Routes
//<editor-fold desc="Artist Routes">
Route::get('/artist/pieces', ['uses' => 'PieceController@artistPieces'])->middleware('role:Artist')->name('artist.pieces');
Route::get('/piece/create', function () {

    return view('piece_create');
})->middleware(['auth', 'role:Artist'])->name('piece.create');
Route::get('/piece/edit/{piece_id}', function ($piece_id) {

    $piece = \App\Piece::find($piece_id);
    return view('piece_edit', ['piece' => $piece]);
})->middleware(['auth', 'role:Artist']);
Route::post('/piece/store', ['uses' => 'PieceController@store'])->middleware(['auth', 'role:Artist']);
Route::post('/piece/edit/{pieceId}', ['uses' => 'PieceController@editPiece'])->middleware(['auth', 'role:Artist']);
Route::delete('/piece/delete/{pieceId}', ['uses' => 'PieceController@deletePiece'])->middleware(['auth', 'role:Artist']);
//</editor-fold>


//<editor-fold desc="Piece Routes">

Route::get('/gallery/{categoryId}/{artistId}', ['uses' => 'PieceController@showAvailablePieces'])->name('gallery');
Route::get('/piece/view/{pieceId}', ['uses' => 'PieceController@showPiece'])->name('piece.view');

//</editor-fold>


//Route::get('/', 'PieceController@showAllPosts')->name('welcome');
Route::get('/', function () {

    $pieces = \App\Piece::all();
    $categories = \App\Category::all();
    return view('welcome', ['uses' => 'PieceController@showAllPosts', 'as' => 'welcome', 'pieces' => $pieces, 'categories' => $categories]);
})->name('home');
Route::get('/home', function () {

    $pieces = \App\Piece::all();
    $categories = \App\Category::all();
    return view('home', ['uses' => 'PieceController@showAllPosts', 'as' => 'welcome', 'pieces' => $pieces, 'categories' => $categories]);
})->name('home');
//Route::get('/home', 'HomeController@index')->name('home');
Route::get('/about',function (){
    return view('about_us');
})->name('about.us');

Route::get('/artists', ['uses' => 'ArtistController@showAllArtists'])->name('artists');
Route::get('/cart', ['uses' => 'UserController@checkOutUser'])->middleware('auth')->name('cart');
Route::post('/like', ['uses' => 'LikeController@postLike'])->middleware('auth')->name('like');
Route::post('/cart', ['uses' => 'PieceController@addPieceToCart'])->middleware('auth')->name('cart.add');

// Payments

Route::get('pesapalRedirect/{user_id}',['uses'=>'PesaPalController@redirectToPesapal'])->name('pp.redirect');
Route::get('pesapalUrl/{user_id}',['uses'=>'PesaPalController@getPesaPalIframeUrl'])->name('pp.url');
Route::get('downloads', function (){
    return view('mobile_apps');
})->name('downloads');
Route::post('coupon/acquire',['uses'=>'CouponController@acquireCoupon'])->name('coupon.acquire');
