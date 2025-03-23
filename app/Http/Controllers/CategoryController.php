<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function createCategory(Request $request){

        $user_id = $request->header('id');

        Category::create([
            'name' => $request->name,
            'user_id' => $user_id,
        ]); 
        return response()->json([
            'status'=>'success',
            'message'=>'Category created successfully'
        ],200);

    } //end method

    public function categoryList(Request $request){
        $user_id = $request->header('id');
        $category = Category::where('user_id',$user_id)->get();
        return $category;
    } //end method

    public function categoryById(Request $request){
        $category = Category::find($request->id);
        return $category;
    } //end method


    public function CategoryUpdate(Request $request){
        $category = Category::find($request->id);
        $category->name = $request->name;
        $category->save();
        return response()->json([
            'status'=>'success',
            'message'=>'Category updated successfully'
        ],200);
    }//end method


    public function CategoryDelete(Request $request){
        $category = Category::find($request->id);
        $category->delete();
        return response()->json([
            'status'=>'success',
            'message'=>'Category deleted successfully'
        ],200);
    }//end method


}
