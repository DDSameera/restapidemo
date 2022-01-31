<?php

namespace App\Controller;

use App\helpers\Helper;
use App\helpers\TokenHelper;
use App\helpers\ValidatorHelper;
use App\Middleware\RequestMiddleware;
use App\Model\TokenModel;
use App\Model\UserModel;
use App\Request\UserLoginRequest;
use App\Request\UserRegisterRequest;
use Klein\Request;
use Klein\Response;
use stdClass;


class UserController
{
    protected stdClass $configs;


    public function register(Request $request, Response $response)
    {

        // Request Validate
        $isJson = (new RequestMiddleware())->acceptJson();
        if (!$isJson) {
            return Helper::errorResponse("Sorry.Endpoint access only JSON Content", "Validation Error");
        }

        //Get Body Content
        $data = json_decode($request->body());


        // Request Validation
        $validateObject = UserRegisterRequest::getValidateObject($data);
        $validationErrors = ValidatorHelper::validate($validateObject);


        if (count($validationErrors) == 0) {

            // Filtering
            $payload = [
                'firstName' => ValidatorHelper::filter($data->firstName),
                'lastName' => ValidatorHelper::filter($data->lastName),
                'email' => ValidatorHelper::filter($data->email),
                'password' => ValidatorHelper::filter($data->password, 'password'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];


            // Create User
            $userModel = new UserModel();
            $newUser = $userModel->createUser($payload);

            if ($newUser) {
                //Get New User Id
                $userId = UserModel::lastInsertRecordId();

                //Save JWT Token
                $jwtToken = TokenHelper::generateToken($userId);
               $jwtToken = $jwtToken['jwt_token'];

                //Encrypted Token
                $encryptedJWTToken = TokenHelper::crypted($jwtToken);
                (new TokenModel())->createToken($userId, $encryptedJWTToken);


                //Return Response
                $userData = [
                    'user_id' => $userId,
                    'jwt_token' => $encryptedJWTToken
                ];

                return Helper::successResponse($userData, "user created successfully");

            }


            //Return Failed Response
            return Helper::errorResponse([], "User Account Cannot Created");

        }

        //Return Validation Error Response
        return Helper::errorResponse($validationErrors, "Validation Errors");


    }

    public function login(Request $request, Response $response)
    {


        $data = json_decode($request->body());

        $validateObject = UserLoginRequest::getValidateObject($data);
        $validationErrors = ValidatorHelper::validate($validateObject);


        if (count($validationErrors) == 0) {
            $payLoads = [
                'email' => ValidatorHelper::filter($data->email),
                'password' => $data->password,
            ];


            $userModel = new UserModel();
            $authorizedUser = $userModel->authenticate($payLoads['email'], $payLoads['password']);

            if ($authorizedUser) {

                //Token update
                $tokenModel = new TokenModel();
                $user = $userModel->checkAlreadyExsist('email', $payLoads['email']);
                $jwtToken = TokenHelper::generateToken($user['id']);
                $jwtToken = $jwtToken['jwt_token'];
                $encryptedJWTToken = TokenHelper::crypted($jwtToken); //Encrypt Token
                $tokenModel->updateToken($user['id'], $encryptedJWTToken);

                //Return Success Response
                return Helper::successResponse($authorizedUser, "User logged successfully");

            }
            //Return Success Response
            return Helper::errorResponse([], "Username or Password is invalid");


        }

        //Return Validation Error Response
        return Helper::errorResponse($validationErrors, "Validation Errors");

    }


}