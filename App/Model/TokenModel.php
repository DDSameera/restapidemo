<?php

namespace App\Model;

class TokenModel extends Model
{

    function createToken($userId, $jwtToken)
    {


        $sql = "INSERT INTO `db_token` (user_id,jwt_token) VALUES (:user_id,:jwt_token)";
        Parent::query($sql);
        Parent::bindParams('user_id', $userId);
        Parent::bindParams('jwt_token', $jwtToken);
        Parent::execute($sql);

    }


    function updateToken($userId, $jwtToken)
    {

        $sql = "UPDATE `db_token` SET jwt_token=:jwt_token WHERE user_id=:user_id";
        Parent::query($sql);
        Parent::bindParams('user_id', $userId);
        Parent::bindParams('jwt_token', $jwtToken);
        Parent::execute($sql);

    }

    function fetchToken($userToken)
    {
        $sql = "SELECT * FROM  `db_token` WHERE jwt_token=:jwt_token";

        Parent::query($sql);
        Parent::bindParams('jwt_token', $userToken);
        Parent::execute();
        return Parent::fetch();
    }

}