<?php

namespace App\Middleware;

use Klein\Request;

class RequestMiddleware
{

    protected static string $request;
    protected static string $authorization;


    public function __construct()
    {

        Self::$request = $_SERVER["CONTENT_TYPE"];

    }

    public function acceptJson(): bool
    {
        return Self::$request == "application/json";

    }


}