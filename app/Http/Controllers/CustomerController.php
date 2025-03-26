<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
   
    public function createCategory(Request $request){

        $user_id = $request->header('id');

        Customer::create([
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
        $category = Customer::where('user_id',$user_id)->get();
        return $category;
    } //end method

    public function categoryById(Request $request){
        $category = Customer::find($request->id);
        return $category;
    } //end method


    public function CategoryUpdate(Request $request){
        $category = Customer::find($request->id);
        $category->name = $request->name;
        $category->save();
        return response()->json([
            'status'=>'success',
            'message'=>'Category updated successfully'
        ],200);
    }//end method


    public function CategoryDelete(Request $request){
        $category = Customer::find($request->id);
        $category->delete();
        return response()->json([
            'status'=>'success',
            'message'=>'Category deleted successfully'
        ],200);
    }//end method

}
