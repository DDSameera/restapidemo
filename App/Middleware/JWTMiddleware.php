<?php

namespace App\Middleware;

use App\helpers\TokenHelper;
use App\Model\TokenModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTMiddleware
{


    public static function validateToken($userToken)
    {

        $tokenModel = new TokenModel();
        $dbToken = $tokenModel->fetchToken($userToken);

        if ($dbToken) {
            $token = $dbToken['jwt_token'];

            $configs = (object )include('App/Config/Config.php');
            $key = $configs->privateKey;

            $decryptedToken = TokenHelper::crypted($token, 'decrypted');

            return JWT::decode($decryptedToken, new Key($key, 'HS256'));
            
        }

        return [];
    }

}