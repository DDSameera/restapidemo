<?php

namespace App\helpers;

use Klein\Response;

class Helper
{

    public static function successResponse($data, $message)
    {
        $response = new Response();

        return $response->code(201)->json([
            "status" => true,
            "message" => (!empty($message)) ? $message : "",
            "data" => $data

        ]);
    }

    public static function errorResponse($data, $message)
    {
        $response = new Response();


        $responseMessage = [
            "status" => false,
            "message" => (!empty($message)) ? $message : "",

        ];

        if ($data != null) {
            $responseMessage['errors'] = $data;
        }

        return $response->code(500)->json($responseMessage);

    }

}