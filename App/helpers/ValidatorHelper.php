<?php

namespace App\helpers;

use App\Middleware\RequestMiddleware;
use App\Model\UserModel;
use App\Request\UserRegisterRequest;

class ValidatorHelper
{
    public static function validate($payloads, $authRequired = false)
    {

        $userModel = new UserModel();

        $response = [];
        foreach ($payloads as $payload) {

            $validator = $payload->validator;
            $key = $payload->key;
            $data = $payload->data;

            if (in_array('required', $validator)) {
                if ($data == null || !isset($data) || $data == '') {
                    $response = [
                        'key' => $key,
                        'message' => "$key is required"
                    ];
                }
            }

            if (in_array('email', $validator)) {

                if (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
                    $response = [
                        'key' => $key,
                        'message' => "$key filed contains invalid content."
                    ];
                }

            }

            if (in_array('dbAlready', $validator)) {


                if ($userModel->checkAlreadyExsist($key, $data)) {
                    $response = [
                        'key' => $key,
                        'message' => "$key already exists"
                    ];
                }


            }

            //Default- Check Request whether JSON or not
            $isJson = (new RequestMiddleware())->acceptJson();
            if (!$isJson) {
                $response = [
                    'key' => 'Content Type Error',
                    'message' => "Invalid Content Type"
                ];
            }


        }


        return $response;


    }


    public static function filter($value, $type = null): string
    {

        return match (true) {
            $type === "password" => password_hash($value, PASSWORD_BCRYPT),
            default => htmlspecialchars(stripcslashes(strip_tags($value))),
        };

    }
}