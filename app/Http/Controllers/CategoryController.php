<?php

namespace App\Http\Controllers;

use App\Category;
use App\Piece;
use App\Like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
       //get All categories
     function getCategories(){
        return response()->json([
            'categories' => Category::all()
        ]);
       }
    
         //get a particular category
       function getCategory($categoryId){
        $category = Category::find($categoryId);
    
        if (!$category) {
            return response()->json([
                'message' => 'Category not found',
                'status' => false
            ]);
        }
        
       
    
        return response()->json([
            'category' => $category,
            'status' => true
        ],200,[], JSON_NUMERIC_CHECK);
        }
        
        
        //Get Catetegory Pieces
        
        function getCategoryPieces($categoryId,$userId){
        $category = Category::find($categoryId);
    
        if (!$category) {
            return response()->json([
                'message' => 'Category not found',
                'status' => false
            ]);
        }
        
        $pieces = Piece::where('category_id',$category->id)->get();
        
        foreach ($pieces as $piece) {
            $likes = Like::where('piece_id',$piece->id)->get();
            if($likes->contains('user_id',$userId)){
                $piece->like_status = true;
            }
            else{
                $piece->like_status = false;
            }
            
            $piece->like_counts = count($likes);
        }
    
        return response()->json([
            'category' => $category,
            'pieces' => $pieces,
            'status' => true
        ],200,[], JSON_NUMERIC_CHECK);
        }
    
         //create an category
        function postCategory(Request $request){
            
              $validator = Validator::make($request->all(), [
            'name' => 'required'
           
        ]);

        if ($validator->fails()) {

            //pass validator errors as errors object for ajax response

            return response()->json([
                'errors' => $validator->errors(),
                'status' => false]);
        }

  
        $category = new Category([
            'name' => $request->input('name')
        ]);
    
        $category->save();
    
        return response()->json([
            'category' => $category,
            'status' => true
        ],200,[], JSON_NUMERIC_CHECK);
    
    
    }
      
    
    //edit an category
    function putCategory(Request $request, $categoryId){
        $category = Category::find($categoryId);
    
        if (!$category) {
            return response()->json([
                'message' => 'category not found',
                'status' => false
            ]);
        }
    
        $category->update([
            'name' => $request->input('name')
        ]);
    
        return response()->json([
            'category' => $category,
            'status' => true
        ],200,[], JSON_NUMERIC_CHECK);
    }
    
    
    //delete an category
    function deleteCategory($categoryId){
        $category = Category::find($categoryId);
    
        if (!$category) {
            return response()->json([
                'message' => 'Category not found',
                'status' => false
            ]);
        }
    
        $category->delete();
        return response()->json(['message' => 'The Category has been deleted'], 200);
    
    }  
}
