<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
       //get All categories
     function getCategoriess(){
        return response()->json([
            'categories' => Category::all()
        ]);
       }
    
         //get a particular category
       function getCategory($categoryId){
        $category = Category::find($categoryId);
    
        if ($category) {
            return response()->json([
                'message' => 'Category not found',
                'status' => false
            ]);
        }
    
        return response()->json([
            'category' => $category,
            'status' => true
        ]);
        }
    
         //create an category
        function postCategory(Request $request){
  
        $category = new Category([
            'name' => $request->input('name')
        ]);
    
        $category->save();
    
        return response()->json([
            'category' => $category,
            'status' => true
        ]);
    
    
    }
      
    
    //edit an category
    function putCategory(Request $request, $categoryId){
        $category = Category::find($categoryId);
    
        if ($category) {
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
        ]);
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
