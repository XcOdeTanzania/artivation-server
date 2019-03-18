<?php

namespace App\Http\Controllers;

use App\Artist;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use http\Env\Response;

class ArtistController extends Controller
{

    //get All artits
   function getArtists(){
     $artists =Artist::all();

     foreach($artists as $artist){
         $user = User::find($artist->user_id);
         $artist->photo_url = $user->photo_url;
         $artist->name = $user->username;
     }
    return response()->json([
        'artists' => $artists
    ]);
   }


     //get a particular artist
   function getArtist($artistId){
    $artist = Artist::find($artistId);

    if (!$artist) {
        return response()->json([
            'message' => 'Artist not found',
            'status' => false
        ]);
    }

    return response()->json([
        'artist' => $artist,
        'status' => true
    ]);
    }

     //create an artist
    function postArtist(Request $request){

    $validator = Validator::make($request->all(), [
        'user_id' => 'required'
    ]);

    if ($validator->fails()) {

        //pass validator errors as errors object for ajax response

        return response()->json([
            'errors' => $validator->errors(),
            'status' => false]);
    }

    $user = User::find($request->input('user_id'));

    if (!$user) {
        return response()->json([
            'message' => 'user not found',
            'status' => false
        ]);
    }
  
    $artist = new Artist([
        'number_of_pieces' => $request->input('number_of_pieces'),
        'number_of_pieces_bought' => $request->input('number_of_pieces_bought'),
        'number_of_likes' => $request->input('number_of_likes'),
        'ratings' => $request->input('ratings')
    ]);

    $user->artists()->save($artist);

    return response()->json([
        'artist' => $artist,
        'status' => true
    ]);


}
  

//edit an artist
function putArtist(Request $request, $artistId){
    $artist = Artist::find($artistId);

    if ($artist) {
        return response()->json([
            'message' => 'artist not found',
            'status' => false
        ]);
    }

    $artist->update([
        'user_id' => $request->input('user_id'),
        'number_of_pieces' => $request->input('number_of_pieces'),
        'number_of_pieces_bought' => $request->input('number_of_pieces_bought'),
        'number_of_likes' => $request->input('number_of_likes'),
        'ratings' => $request->input('ratings')
    ]);

    return response()->json([
        'artist' => $artist,
        'status' => true
    ]);
}


//delete an artist
function deleteArtist($artistId){
    $artist = Artist::find($artistId);

    if (!$artist) {
        return response()->json([
            'message' => 'Artist not found',
            'status' => false
        ]);
    }

    $artist->delete();
    return response()->json(['message' => 'The Artist has been deleted'], 200);

}

}
