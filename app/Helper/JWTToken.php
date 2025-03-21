<?php

namespace App\Helper;

use Firebase\JWT\JWT;
use Exception;

class JWTToken
{
    public static function CreateToken($userEmail, $userId)
    {
        $key = env("JWT_KEY", "your_secret_key"); 
        $payload = [
            "iss" => "Laravel-token",
            "iat" => time(),
            "exp" => time() + 60*60*24*30, // 30 days
            "userEmail" => $userEmail,
            "userId" => $userId
        ];
        return JWT::encode($payload, $key, 'HS256');
    }
    public static function VerifyToken($token):string|object
    {
      try{

        if($token == null){
            return "unauthorized";
        }else{
            $key = env("JWT_KEY");
            $decoded = JWT::decode($token, new \Firebase\JWT\Key($key, 'HS256'));
                return $decoded;
            }
        }
        catch(Exception $e){
            return "unauthorized";
        }
    }
    
    
    public static function CreateTokenSetPassword($userEmail)
    {
        $key = env("JWT_KEY", "your_secret_key"); 
        $payload = [
            "iss" => "Laravel-token",
            "iat" => time(),
            "exp" => time() + 60*60*24*30, // 30 days
            "userEmail" => $userEmail,
            "userId" => "0"
        ];
        return JWT::encode($payload, $key, 'HS256');
    }
}
