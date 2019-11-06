<?php

namespace App\Http\Controllers;

use App\Artist;
use App\Category;
use App\User;
use App\Piece;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArtistController extends Controller
{

    //get All artits
    function getArtists($web = false)
    {
        $artists = Artist::all();

        foreach ($artists as $artist) {
            $user = User::find($artist->user_id);
            $artist->photo_url = $user->photo_url;
            $artist->name = $user->username;
            $artist->number_of_pieces = Piece::where('artist_id', $artist->id)->count();
            $artist->number_of_pieces_bought = Piece::where('artist_id', $artist->id)->whereNotNull('purchased_by')->count();
        }

        if ($web) return $artists;

        return response()->json([
            'artists' => $artists
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    function showAllArtists()
    {
        $artists = $this->getArtists(true);
        return view('artists', ['artists' => $artists]);
    }


    //get a particular artist
    function getArtist($artistId)
    {
        $artist = Artist::find($artistId);

        if (!$artist) {
            return response()->json([
                'message' => 'Artist not found',
                'status' => false
            ]);
        }

        $pieces = Piece::where('artist_id', $artistId)->get();

        return response()->json([
            'artist' => $artist,
            'pieces' => $pieces,
            'status' => true
        ], 200, [], JSON_NUMERIC_CHECK);
    }

    //create an artist
    function postArtist(Request $request)
    {

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
            'ratings' => $request->input('ratings'),
            'folder'=>$user->username
        ]);

        $user->artists()->save($artist);

        return response()->json([
            'artist' => $artist,
            'status' => true
        ], 200, [], JSON_NUMERIC_CHECK);


    }


//edit an artist
    function putArtist(Request $request, $artistId)
    {
        $artist = Artist::find($artistId);

        if (!$artist) {
            return response()->json([
                'message' => 'artist not found',
                'status' => false
            ]);
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'number_of_pieces' => 'required',
            'number_of_pieces_bought' => 'required',
            'number_of_likes' => 'required',
            'ratings' => 'required'
        ]);

        if ($validator->fails()) {

            //pass validator errors as errors object for ajax response

            return response()->json([
                'errors' => $validator->errors(),
                'status' => false]);
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
        ], 200, [], JSON_NUMERIC_CHECK);
    }


//delete an artist
    function deleteArtist($artistId)
    {
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
