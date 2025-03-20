<?php

namespace App\Helper;

use Firebase\JWT\JWT;

class JWTToken
{
    public static function CreateToken($userEmail, $userId)
    {
        $key = env("JWT_KEY", "your_secret_key"); // Ensure JWT_KEY is set in .env
        $payload = [
            "iss" => "Laravel-token",
            "iat" => time(),
            "exp" => time() + 60*60*24*30, // 30 days
            "userEmail" => $userEmail,
            "userId" => $userId
        ];
        return JWT::encode($payload, $key, 'HS256');
    }
}
