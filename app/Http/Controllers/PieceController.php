<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Piece;
use App\Category;
use App\Artist;
use App\User;
use App\Like;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Request as RequestOrigin;

//use http\Env\Response;


class PieceController extends Controller
{

    public function showAllPosts()
    {
        $pieces = Piece::all();
        foreach ($pieces as $piece) {
            $imagedetails = getimagesize(storage_path('app/' . str_replace('/api/piece/image/', '', $piece->image)));

            $piece->width = $imagedetails[0];
            $piece->height = $imagedetails[1];
        }

        return view('welcome', ['pieces' => $pieces]);
    }

    public function showPiece($pieceId)
    {

        $accessibility = false;

        $piece = Piece::find($pieceId);

        if (Auth::user()) $userId = Auth::user()->id;
        else  $userId = null;

        $artist = Artist::find($piece->artist_id)->first();
        $artist = $artist->user()->first();
        $piece['artist'] = $artist->username;
        $piece['category'] = Category::find($piece->category_id)->first()->name;
        $collection = Like::all();
        $filtered_collection = $collection->filter(function ($item) use ($pieceId) {
            if ($item->piece_id == $pieceId)

                return $item;
        })->values();

        $piece['like_counts'] = count($filtered_collection);
        if ($userId && Like::whereUserId($userId)->wherePieceId($piece->id)->exists()) {
            $piece['like_status'] = true;


        } else {
            $piece['like_status'] = false;
        }

        $usersCount = User::all()->count();
        $rate = round(count($filtered_collection) / $usersCount) * 5 > 0 ? round(count($filtered_collection) / $usersCount) * 5 : 1;
        $piece['rate'] = $rate;

        $cart = $piece->cart()->first();


        if ($userId && $cart && $cart->user_id == $userId) {
            $piece['cart_status'] = true;
            $accessibility = true;
        } else {
            $piece['cart_status'] = false;
            if (!$cart) $accessibility = true;
            if ($cart) {
                $invisibilityDeadLine = new Carbon($cart['updated_at']);
                $invisibilityDeadLine->addDays(7);
                if ($invisibilityDeadLine <= today()) $accessibility = true;
            }
        }
        return view('view_piece', ['piece' => $piece, 'accessibility' => $accessibility]);
    }

    //<editor-fold desc="Show avaiable pieces for given artist in given category ">

    /**
     * @param $categoryId
     * @param $artistId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showAvailablePieces($categoryId, $artistId)
    {

        $userId = null;
        $artists = Artist::all();

        if ($categoryId == 0) {
            $activeCategory['id'] = 0;
            $activeCategory['name'] = 'All Categories';
        } else {
            $activeCategory['id'] = Category::find($categoryId)['id'];
            $activeCategory['name'] = Category::find($categoryId)['name'];
        }

        if ($artistId == 0) {
            $activeArtist['id'] = 0;
            $activeArtist['name'] = 'All Artist';
        } else {
            $activeArtist['id'] = Artist::find($artistId)['id'];
            $activeArtist['name'] = Artist::find($artistId)->user()->first()['username'];
        }

        if (Auth::user()) $userId = Auth::user()->id;

        if ($categoryId == 0 && $artistId == 0) {
            $pieces = $this->sortAvailableUserPieces(Piece::all(), $userId);
            return view('gallery', ['pieces' => $pieces, 'artists' => $artists, 'activeArtist' => $activeArtist, 'activeCategory' => $activeCategory]);
        } else if ($categoryId == 0 && $artistId != 0) {
            $pieces = $this->sortAvailableUserPieces(Piece::whereArtistId($artistId)->get(), $userId);
            return view('gallery', ['pieces' => $pieces, 'artists' => $artists, 'artists' => $artists, 'activeArtist' => $activeArtist, 'activeCategory' => $activeCategory]);
        } else if ($categoryId != 0 && $artistId == 0) {
            $pieces = $this->sortAvailableUserPieces(Piece::whereCategoryId($categoryId)->get(), $userId);
            return view('gallery', ['pieces' => $pieces, 'artists' => $artists, 'artists' => $artists, 'activeArtist' => $activeArtist, 'activeCategory' => $activeCategory]);
        } else {
            $pieces = Piece::where([
                ['artist_id', $artistId],
                ['category_id', $categoryId]
            ])->get();
            $pieces = $this->sortAvailableUserPieces($pieces, $userId);
            return view('gallery', ['pieces' => $pieces, 'artists' => $artists, 'artists' => $artists, 'activeArtist' => $activeArtist, 'activeCategory' => $activeCategory]);
        }
    }
    //</editor-fold>

    //<editor-fold desc="Sort user available Pieces">

    /**
     * @param $pieces -The pieces collection to sort from
     * @param $user_id - The id of the user
     * @return \Illuminate\Support\Collection
     */
    public function sortAvailableUserPieces($pieces, $user_id)
    {
        $availablePieces = collect(new Piece);
        $usersCount = User::all()->count();

        foreach ($pieces as $piece) {
            $pieceId = $piece->id;
            $piece['image'] = site_url . $piece->image;
            $artist = Artist::find($piece->artist_id)->first();
            $artist = $artist->user()->first();
            $piece['artist'] = $artist->username;
            $piece['category'] = Category::find($piece->category_id)->first()->name;
            $collection = Like::all();
            $filtered_collection = $collection->filter(function ($item) use ($pieceId) {
                if ($item->piece_id == $pieceId)

                    return $item;
            })->values();

            $piece['like_counts'] = count($filtered_collection);
            if ($user_id && Like::whereUserId($user_id)->wherePieceId($piece->id)->exists()) {
                $piece['like_status'] = true;


            } else {
                $piece['like_status'] = false;
            }


            $rate = round(count($filtered_collection) / $usersCount) * 5 > 0 ? round(count($filtered_collection) / $usersCount) * 5 : 1;
            $piece['rate'] = $rate;

            $cart = $piece->cart()->first();


            if ($user_id && $cart && $cart->user_id == $user_id) {
                $piece['cart_status'] = true;
                $availablePieces->push($piece);
            } else {
                $piece['cart_status'] = false;
                if (!$cart) $availablePieces->push($piece);
                if ($cart) {
                    $invisibilityDeadLine = new Carbon($cart['updated_at']);
                    $invisibilityDeadLine->addDays(7);
                    if ($invisibilityDeadLine <= today()) $availablePieces->push($piece);
                }
            }
        }

        return $availablePieces;
    }
    //</editor-fold>

    //<editor-fold desc="Get user pieces API for mobile">
    /**
     * @param $user_id
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Support\Collection
     */

    //get All artits
    public function getPieces($user_id)
    {

        $pieces = Piece::all();

        $availablePieces = collect(new Piece);
        $usersCount = User::all()->count();

        foreach ($pieces as $piece) {
            $pieceId = $piece->id;
            $piece['image'] = site_url . $piece->image;
            $artist = Artist::find($piece->artist_id)->first();
            $artist = $artist->user()->first();
            $piece['artist'] = $artist->username;
            $piece['category'] = Category::find($piece->category_id)->first()->name;
            $collection = Like::all();
            $filtered_collection = $collection->filter(function ($item) use ($pieceId) {
                if ($item->piece_id == $pieceId)

                    return $item;
            })->values();

            $piece['like_counts'] = count($filtered_collection);
            if ($user_id && Like::whereUserId($user_id)->wherePieceId($piece->id)->exists()) {
                $piece['like_status'] = true;


            } else {
                $piece['like_status'] = false;
            }


            $rate = round(count($filtered_collection) / $usersCount) * 5 > 0 ? round(count($filtered_collection) / $usersCount) * 5 : 1;
            $piece['rate'] = $rate;

            $cart = $piece->cart()->first();


            if ($user_id && $cart && $cart->user_id == $user_id) {
                $piece['cart_status'] = true;
                $availablePieces->push($piece);
            } else {
                $piece['cart_status'] = false;
                if (!$cart) $availablePieces->push($piece);
                if ($cart) {
                    $invisibilityDeadLine = new Carbon($cart['updated_at']);
                    $invisibilityDeadLine->addDays(7);
                    if ($invisibilityDeadLine <= today()) $availablePieces->push($piece);
                }
            }
        }


//        $filtered_collection = $pieces->filter(function ($item) use ($user_id) {
//            if (($item->purchased_by == $user_id || $item->purchased_by == null))
//                return $item;
//        })->values();

        return response()->json(['pieces' => $availablePieces,
            'count' => count($availablePieces)], 200, [], JSON_NUMERIC_CHECK);
        // return response()->json([
        //     'pieces' => Piece::all()
        // ]);
    }

    //</editor-fold>


    public function store(Request $request)
    {

        $site_url = "http://localhost:8001/api/piece/image/";
        //$site_url = "http://192.168.1.107:8001/api/piece/image/";
        //$site_url = "http://www.artivation.co.tz/backend/api/piece/image/";

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'category_id' => 'required',
            'price' => 'required',
            'desc' => 'required',
            'size' => 'required',
        ]);

        if ($validator->fails()) {

            //pass validator errors as errors object for ajax response

            return back()->with('error', $validator->errors());
        }

        $user = Auth::user();
        if (!$user)
            return back()->with('error', 'Not Authenticated');

        $artist = Artist::whereUserId($user->id)->first();
        if (!$artist) {
            return back()->with('error', 'Artist not found');
        }


        if ($request->hasFile('file')) {

            $path = $request->file('file')->store($artist->folder);

            $piece = new Piece([
                'image' => '/api/piece/image/' . $path,
                'price' => $request->input('price'),
                'title' => $request->input('title'),
                'size' => $request->input('size'),
                'desc' => $request->input('desc'),
                'rate' => $request->input('rate'),
                'category_id' => $request->input('category_id'),
                'cart_status' => $request->input('cart_status'),
                'favorite_list' => $request->input('favorite_list'),
            ]);

            $artist->pieces()->save($piece);

            return back()->with('msg', 'Piece posted successfully');
        }
        return back()->with('error', 'File not found');
    }

    public function editPiece(Request $request, $pieceId)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'category_id' => 'required',
            'price' => 'required',
            'desc' => 'required',
            'size' => 'required',
        ]);

        if ($validator->fails()) {

            //pass validator errors as errors object for ajax response

            return back()->with('error', $validator->errors());
        }

        $user = Auth::user();
        if (!$user)
            return back()->with('error', 'Not Authenticated');

        $artist = Artist::whereUserId($user->id)->first();
        if (!$artist) {
            return back()->with('error', 'Artist not found');
        }

        $piece = Piece::find($pieceId);
        if (!$piece)
            return back()->with('error', 'Piece not found');

        if ($piece->artist_id != $artist->id)
            return back()->with('error', 'Not authorized');


        if ($request->hasFile('file')) {

            $path = $request->file('file')->store($artist->folder);
            $piece->image = '/api/piece/image/' . $path;
        }
        $piece->price = $request->input('price');
        $piece->title = $request->input('title');
        $piece->size = $request->input('size');
        $piece->desc = $request->input('desc');
        $piece->rate = $request->input('rate');
        $piece->category_id = $request->input('category_id');

        $piece->update();


        return back()->with('msg', 'Piece updated successfully')->withInput(['piece' => $piece]);


    }

    //get a particular piece
    function getPiece($pieceId)
    {

        $piece = Piece::find($pieceId);
        $piece->favoriteList = unserialize($piece->favoriteList);

        if (!$piece) {
            return response()->json([
                'message' => 'Piece not found',
                'status' => false
            ]);
        }

        $piece['image'] = site_url . $piece->image;

        return response()->json([
            'piece' => $piece,
            'status' => true
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    //create an piece
    function postPiece(Request $request)
    {

        //$site_url = "http://192.168.1.107:8000/api/piece/image/";
        $site_url = "http://www.artivation.co.tz/backend/api/piece/image/";

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'category_id' => 'required',
            'price' => 'required',
            'desc' => 'required',
            'artist_id' => 'required',
            'size' => 'required',
        ]);

        if ($validator->fails()) {

            //pass validator errors as errors object for ajax response

            return response()->json([
                'errors' => $validator->errors(),
                'status' => false]);
        }


        $artist = Artist::find($request->input('artist_id'));
        if (!$artist) {
            return response()->json(['message' => 'Artist not found'], 404);
        }


        if ($request->hasFile('file')) {

            $path = $request->file('file')->store($artist->folder);

            $piece = new Piece([
                'image' => '/api/piece/image/' . $path,    //$site_url . $path,
                'price' => $request->input('price'),
                'title' => $request->input('title'),
                'size' => $request->input('size'),
                'desc' => $request->input('desc'),
                'rate' => $request->input('rate'),
                'category_id' => $request->input('category_id'),
                'cart_status' => $request->input('cart_status'),
                'favorite_list' => $request->input('favorite_list'),
            ]);

            $artist->pieces()->save($piece);

            return response()->json(['piece' => $piece], 201, [], JSON_NUMERIC_CHECK);
        }

        return response()->json(['Error' => 'File not found'], 404);

    }

    public function viewPiece($pieceImage)
    {
        //  $piece = Piece::find($pieceId);


        $pathToFile = storage_path('/app/' . $pieceImage);

        return response()->download($pathToFile);
    }

    public function viewImage($folder = '', $pieceImage)
    {
        //  $piece = Piece::find($pieceId);


        $pathToFile = storage_path('/app/' . $folder . '/' . $pieceImage);

        return response()->download($pathToFile);
    }


    //edit a piece
    function putPiece(Request $request, $pieceId)
    {

        $piece = Piece::find($pieceId);

        if (!$piece) {
            return response()->json([
                'message' => 'piece not found',
                'status' => false
            ]);
        }

        $validator = Validator::make($request->all(), [
            'price' => 'required',
            'title' => 'required',
            'size' => 'required',
            'desc' => 'required'

        ]);

        if ($validator->fails()) {

            //pass validator errors as errors object for ajax response

            return response()->json([
                'errors' => $validator->errors(),
                'status' => false]);
        }

        $piece->update([
            'price' => $request->input('price'),
            'title' => $request->input('title'),
            'size' => $request->input('size'),
            'desc' => $request->input('desc'),
        ]);

        return response()->json([
            'piece' => $piece,
            'status' => true
        ]);
    }


    //delete an piece
    function deletePiece($pieceId)
    {
        $piece = Piece::find($pieceId);

        if (!$piece) {

            return response()->json([
                'message' => 'Piece not found',
                'status' => false
            ]);
        }

        $piece->delete();
        return response()->json(['message' => 'The Piece has been deleted'], 200);

    }

    //Additional operations...
    function getPurchasedPieces($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'status' => false
            ]);
        }

        $piece = Piece::all();

        $purchased_pieces = $piece->filter(function ($item) use ($userId) {
            if ($item->purchased_by == $userId)
                return $item;
        })->values();

        foreach ($purchased_pieces as $piece) {
            $piece['image'] = site_url . $piece->image;
            $likes = Like::where('piece_id', $piece->id)->get();
            if ($likes->contains('user_id', $userId)) {
                $piece->like_status = true;
            } else {
                $piece->like_status = false;
            }
            $piece->like_counts = count($likes);

        }

        return response()->json([
            'pieces' => $purchased_pieces
        ], 200,
            [], JSON_NUMERIC_CHECK);
    }

    function addPieceToCart(Request $request)
    {
        $status = false;
        $validator = Validator::make($request->all(), [
            'piece_id' => 'required',
            'user_id' => 'required'
        ]);

        if ($validator->fails()) {

            //pass validator errors as errors object for ajax response

            return response()->json([
                'errors' => $validator->errors(),
                'status' => false]);
        }

        $piece = Piece::find($request->input('piece_id'));

        if (!$piece) {
            return response()->json([
                'message' => 'piece not found',
                'status' => false
            ], 404);
        }

        $user = User::find($request->input('user_id'));

        if (!$user) {
            return response()->json([
                'message' => 'Invalid user, user not found',
                'status' => false
            ], 404);
        }

        $cart = Cart::wherePieceId($piece->id)->first();


        if ($cart) {
            if ($cart->user_id == $user->id)
                $cart->destroy($cart->id);
            else {
                $cart->update([
                    'user_id' => $user->id,
                ]);
                $status = true;
            }
        } else {
            $cart = new Cart([
                'user_id' => $user->id,
                'piece_id' => $piece->id
            ]);
            $cart->save();
            $status = true;
        }


//        if (is_null($piece->purchased_by)) {
//            $status = true;
//            $piece->update([
//                'purchased_by' => $request->input('user_id'),
//            ]);
//        } else if ($piece->purchased_by == $request->input('user_id')) {
//            $status = false;
//            $piece->update([
//                'purchased_by' => null,
//            ]);
//        }
        if (RequestOrigin::is('api*'))
            return response()->json([
                'piece' => $piece,
                'status' => $status,

            ], 200, [], JSON_NUMERIC_CHECK);

        $arr = array('msg' => 'Cart updated', 'pieceId' => $piece->id, 'status' => $status);

        return Response()->json($arr);
    }

    function artistPieces()
    {
        $user = Auth::user();
        if (!$user)
            return back()->with('error', 'Not Authenticated');

        if ($user->role()['name'] != 'Artist')
            return back()->with('error', 'Not Authorized');

        $artist = Artist::whereUserId($user->id)->first();

        if (!$artist)
            return back()->with('error', 'You can\'t access this for now');

        $pieces = Piece::whereArtistId($artist->id)->get();

        return view('my_pieces', ['pieces' => $pieces]);
    }

}
