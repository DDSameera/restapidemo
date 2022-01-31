<?php

namespace App\Request;

use App\Model\UserModel;

class UserLoginRequest
{


    public static function getValidateObject($data)
    {
       return [

            (object)[
                'key' => 'email',
                'data' => $data->email,
                'validator' => [
                    'required',
                    'email',

                ]
            ],
            (object)[
                'key' => 'password',
                'data' => $data->password,
                'validator' => [
                    'required',

                ]
            ],
        ];
    }



}