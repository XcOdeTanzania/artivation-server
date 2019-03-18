<?php

namespace App\Http\Controllers;

use App\Piece;
use App\Category;
use App\Artist;
use App\User;
use App\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use http\Env\Response;


class PieceController extends Controller
{
    
       //get All artits
      public function getPieces($user_id){
        $pieces = Piece::all();
        $allUsers = User::all();
        foreach ($pieces as $piece) {
            $pieceId = $piece->id;
            
            $collection = Like::all();
            $filtered_collection = $collection->filter(function ($item) use ($pieceId)  {
            if ($item->piece_id == $pieceId)
            
                return $item;
            })->values();
            
            if (Like::whereUserId($user_id)->wherePieceId($piece->id)->exists()){
                $piece['like_status'] = true;
                $piece['like_counts'] = count($filtered_collection);
    
                }else{
                $piece['like_status'] = false;
                $piece['like_counts'] = count($filtered_collection);
                }
                
                if($user_id == $piece->purchased_by){
                    $piece['cart_status'] = true;
                }else{
                     $piece['cart_status'] = false;
                }
                $rate = round(count($filtered_collection)/count($allUsers)) * 5 > 0 ? round(count($filtered_collection)/count($allUsers)) * 5 : 1;
                $piece['rate'] = $rate;
        
          }

        $filtered_collection =  $pieces->filter(function ($item) use ($user_id)  {
            if (($item->purchased_by == $user_id || $item->purchased_by == null) )
                return $item;
        })->values();
        
        return response()->json(['pieces' => $filtered_collection,
                                  'count'=> count($filtered_collection)],200);
        // return response()->json([
        //     'pieces' => Piece::all()
        // ]);
       }
    
    
         //get a particular piece
       function getPiece($pieceId){

        $piece = Piece::find($pieceId);
        $piece->favoriteList = unserialize($piece->favoriteList);
      
        if (!$piece) {
            return response()->json([
                'message' => 'Piece not found',
                'status' => false
            ]);
        }
    
        return response()->json([
            'piece' => $piece,
            'status' => true
        ]);
        }
    
         //create an piece
        function postPiece(Request $request){

        $site_url = "http://192.168.1.107:8000/api/piece/image/";
    
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
        if(!$artist){
                 return response()->json(['message'=>'Artist not found'],404);
             }


         if($request->hasFile('file')){

            $path = $request->file('file')->store($artist->folder);

            $piece = new Piece([
                'image' => $site_url.$path,
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

             return response()->json(['piece' => $piece], 201);
        }

        return response()->json(['Error'=>'File not found'],404);
    
    }
      
    public function viewPiece($pieceImage){
      //  $piece = Piece::find($pieceId);


        $pathToFile = storage_path('/app/'.$pieceImage);

        return response()->download($pathToFile);
    } 


    //edit an piece
    function putPiece(Request $request, $pieceId){
   
        $piece = Piece::find($pieceId);
    
        if (!$piece) {
            return response()->json([
                'message' => 'piece not found',
                'status' => false
            ]);
        }


        $piece->update([
            'cart_status' => $request->input('cart_status'),
            'favorite_list' => $request->input('favorite_list'),
        ]);
    
        return response()->json([
            'piece' => $piece,
            'status' => true
        ]);
    }
    
    
    //delete an piece
    function deletePiece($pieceId){
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
    function getPurchasedPieces($userId){
        $user = User::find($userId);
    
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
                'status' => false
            ]);
        }

        $piece= Piece::all();

        $purchased_pieces = $piece->filter( function ($item) use ($userId){
                 if($item->purchased_by == $userId)
                 return $item;
        })->values();

        return response()->json([
            'pieces' =>  $purchased_pieces
        ]);
    }

    function addPieceToCart(Request $request){
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
            ]);
        }

        if(is_null($piece->purchased_by)){
            $status = true;
            $piece->update([
                'purchased_by' => $request->input('user_id'),
            ]);
        }else if($piece->purchased_by == $request->input('user_id')){
            $status = false;
            $piece->update([
                'purchased_by' => null,
            ]);
        }
        return response()->json([
            'piece' => $piece,
            'status' => $status
        ]);
    }
}
