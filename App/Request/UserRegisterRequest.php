<?php

namespace App\Request;

use App\Model\UserModel;

class UserRegisterRequest
{


    public static function getValidateObject($data)
    {
       return [
            (object)[
                'key' => 'firstName',
                'data' => $data->firstName,
                'validator' => [
                    'required'
                ]


            ],
            (object)[
                'key' => 'lastName',
                'data' => $data->lastName,
                'validator' => [
                    'required'
                ]

            ],
            (object)[
                'key' => 'email',
                'data' => $data->email,
                'validator' => [
                    'required',
                    'email',
                    'dbAlready'
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