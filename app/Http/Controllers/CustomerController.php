<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
   
    public function createCustomer(Request $request){

        $user_id = $request->header('id');

        $request->validate([
            'name' => 'required',
             'email'=>'required|email|unique:customers,email',
             'mobile'=>'required',
        ]);
        Customer::create([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'mobile'=>$request->input('mobile'),            
            'user_id' => $user_id,
        ]); 
        return response()->json([
            'status'=>'success',
            'message'=>'Customer created successfully'
        ],200);

    } //end method

    public function CustomerList(Request $request){
        $user_id = $request->header('id');
        $customers = Customer::where('user_id',$user_id)->get();
        return $$customers;
    } //end method

    public function CustomerById(Request $request){
        $category = Customer::find($request->id);
        return $category;
    } //end method


    public function CustomerUpdate(Request $request){
        $user_id = $request->header('id');
        $id = $request->input('id');
        Customer::where('id',$id)->update([
            'name'=>$request->input('name'),
            'email'=>$request->input('email'),
            'mobile'=>$request->input('mobile'),
            'user_id' => $user_id,
        ]);
        return response()->json([
            'status'=>'success',
            'message'=>'Customer updated successfully'
        ],200);
    }//end method


    public function CustomerDelete(Request $request){
        $customer = Customer::find($request->id);
        $customer->delete();
        return response()->json([
            'status'=>'success',
            'message'=>'Customer deleted successfully'
        ],200);
    }//end method

}
