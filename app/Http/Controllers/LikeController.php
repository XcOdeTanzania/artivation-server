<?php

namespace App\Http\Controllers;

use App\Like;
use App\Piece;
use App\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    
    public function isLikedByMe($pieceId,$user_id)
    {
        $allUsers = User::all();
        $user = User::find($user_id);
        if(!$user){
            return response()->json(['message'=>'User not found'],404);
        }
        $piece = Piece::findOrFail($pieceId)->first();
        $number_of_likes = Like::wherePieceId($piece->id);
        $collection = Like::all();
        $filtered_collection = $collection->filter(function ($item) use ($pieceId)  {
            if ($item->piece_id == $pieceId)
            
                return $item;
        })->values();
        
      $users=array();
      
      foreach ($filtered_collection as $items) {
        
        $user = User::find($items->user_id);
        
        array_push($users,$user);
      }
      
      $rate = round(count($filtered_collection)/count($allUsers)) * 5 > 0 ? round(count($filtered_collection)/count($allUsers)) * 5 : 1;
        if ((Like::whereUserId($user_id)->wherePieceId($pieceId)->exists()) && Like::whereNull('deleted_at')){
            return response()->json([
                'status'=>true,
                'count'=>count($filtered_collection),
                'likes'=>$filtered_collection,
                'users'=>  $users,
                'rate'=>   $rate
                ],200);
        }
        
        
        return response()->json(['status'=>false,
                                  'count'=>count($filtered_collection),
                                  'likes'=>$filtered_collection,
                                  'users'=>  $users,
                                  'rate'=>   $rate],201);
    }

   public function getUsersWhoLikedPiece($piece_id){
        $piece = Piece::find($piece_id);
       if(!$piece){
            return response()->json(['message'=>'Piece not found'],404);
        }
         $likes = Like::wherePieceId($piece->id);
         
         return response()->json([
                'likes'=>$likes],200);
   }



    public function postLike(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'piece_id' => 'required',
            'user_id'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['message'=>$validator->errors()],404);
        }

        $existing_like = Like::withTrashed()->wherePieceId($request->input('piece_id'))->whereUserId($request->input('user_id'))->first();

        if (is_null($existing_like)) {

         $like =   Like::create([
                'piece_id' => $request->input('piece_id'),
                'user_id' => $request->input('user_id')
            ]);
            
            
            return response()->json(['message'=>'piece has been liked for the first time by this user',
                                     'status'=>$like],200);

       
        } else {
            if (is_null($existing_like->deleted_at)) {
                $existing_like->delete();
                return response()->json(['status'=>'disliked'],201);
            } else {
                $existing_like->restore();
                return response()->json(['status'=>'liked'],200);
            }
        }
    }

}
