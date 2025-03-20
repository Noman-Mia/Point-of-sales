<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use App\Helper\JWTToken;

class UserController extends Controller
{
    public function UserRegistration(Request $request){
       try{
            $request->validate([
                'name'=>'required',
                'email'=>'required|email|unique:users,email',
                'password'=>'required',
            ]);

            $user = User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>$request->password,
            ]);
            return response()->json(
                ['status'=>"success",
                'message'=>'User create Successful',
                'data'=>$user
            ]);

            }catch(Exception $e){
                return response()->json(
                    ['status'=>"fail",
                    'message'=>$e->getMessage()
                ]);
            }
    }


    public function UserLogin(Request $request){
        $count = User::where('email',$request->input('email'))->
         where('password',$request->input('password'))->select('id')->first();
         if($count !== null){
            //User login->jwt token issue
            $token = JWTToken::CreateToken($request->input('email'),$count->id);
            return response()->json(
                ['status'=>"success",
                'message'=>'User login Successful',
                'data'=>$token
            ],200)->cookie('token', $token, 60*24*30);
         }else{
            return response()->json(
                ['status'=>"fail",
                'message'=>'Unauthorized'
            ],200);
         }
    }
}
