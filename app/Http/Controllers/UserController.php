<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Exception;
use App\Helper\JWTToken;
use App\Mail\OTPMail;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class UserController extends Controller
{

  public function LoginPage(){
        return Inertia::render('LoginPage');
    }

    public function RegistrationPage(){
        return Inertia::render('RegistrationPage');
    }

    public function ResetPasswordPage(){
        return Inertia::render('ResetPasswordPage');
    }

    public function VerifyOTPPage(){
        return Inertia::render('VerifyOTPPage');
    }

    public function SendOTPPage(){
        return Inertia::render('SendOTPPage');
    }

    

    public function UserRegistration(Request $request){
       try{
            $request->validate([
                'name'=>'required',
                'email'=>'required|email|unique:users,email',
                'password'=>'required',
                'mobile'=>'required',
            ]);

            $user = User::create([
                'name'=>$request->name,
                'email'=>$request->email,
                'password'=>$request->password,
                'mobile'=>$request->mobile,
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
                'token'=>$token
            ],200)->cookie('token', $token, 60*24*30);
         }else{
            return response()->json(
                ['status'=>"fail",
                'message'=>'Unauthorized'
            ],200);
         }
    }

    public function DashboardPage(Request $request){
        $user = $request->header('email');
        return response()->json([
            'status'=>"success",
            'message'=>'User login Successful',
            'user'=>$user
        ],200);
    }

    public function UserLogout(Request $request){     
        return response()->json([
            'status'=>"success",
            'message'=>'User logout Successful',
        ],200)->cookie('token', '', 1);
    }

    public function SendOTPCode(Request $request){
     $email = $request->input('email');
        $otp = rand(1000,9999);

        $count = User::where('email',$email)->count();

        if($count == 1){
            // Mail::to($email)->send(new OTPMail($otp));
            User::where('email',$email)->update(['otp'=>$otp]);
            return response()->json([
                'status'=>"success",
                'message'=>"4 Digit{$otp} OTP send successfully",
            ],200);
        }else{
            return response()->json([
                'status'=>"fail",
                'message'=>"unauthorized",
            ],200);
        }
    } 

    public function VerifyOTP(Request $request){
        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email',$email)->where('otp',$otp)->count();
        if($count == 1){
            User::where('email',$email)->update(['otp'=>0]);
        $token = JWTToken::CreateTokenSetPassword($request->input('email'));

            return response()->json([
                'status'=>"success",
                'message'=>"OTP verification successfully",
            ],200)->cookie('token', $token, 60*24*30);
        }else{
            return response()->json([
                'status'=>"fail",
                'message'=>"unauthorized",
            ],200);
        }
    }

    public function ResetPassword(Request $request){
        try{
            $email = $request->header('email');
            $password = $request->input('password');
            User::where('email',$email)->update(['password'=>$password]);
            return response()->json([
                'status'=>"success",
                'message'=>"Password reset successfully",
            ],200);

        }catch(Exception $e){
            return response()->json([
                'status'=>"fail",
                'message'=>"something went wrong",
            ],200);

        }
    }
}
