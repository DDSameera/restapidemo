<?php

namespace App\helpers;

use App\Middleware\JWTMiddleware;
use App\Model\TokenModel;
use Firebase\JWT\JWT;

class TokenHelper
{

    public static function generateToken($userId)
    {
        $configs = (object )include('App/Config/Config.php');
        $key = $configs->privateKey;


        $tokenPayload = [
            "iss" => "PHP_REST_API",
            "exp" => strtotime('+7 days'),
            "user_id" => $userId,
        ];

        //Generate Encoded JWT Token
        $jwt = JWT::encode($tokenPayload, $key, 'HS256');
        $tokenPayload ['jwt_token'] = $jwt;


        return $tokenPayload;

    }

    public static function crypted($jwtToken, $type = "")
    {
        $configs = (object )include('App/Config/Config.php');
        $key = $configs->privateKey;
        $iv = $configs->iv;

        if ($type == 'decrypted') {
            //Decrypted Token
            return openssl_decrypt($jwtToken, 'AES-128-CTR', $key, 0, $iv);

        }

        //Encrypted Token
        return openssl_encrypt($jwtToken, 'AES-128-CTR', $key, 0, $iv);

    }

    public static function getUserToken($userToken)
    {

        $userToken = explode('Bearer ', $userToken);
        return $userToken[1];
    }

    public static function validateToken($userToken)
    {


        $userToken = TokenHelper::getUserToken($userToken);
        return JWTMiddleware::validateToken($userToken);

    }
}