<?php

namespace App\Controller;

use App\helpers\Helper;
use App\helpers\TokenHelper;
use App\helpers\ValidatorHelper;
use App\Middleware\JWTMiddleware;
use App\Middleware\RequestMiddleware;
use App\Model\CatalogModel;
use App\Model\TokenModel;
use App\Request\CatalogRequest;
use Klein\Request;
use Klein\Response;

class CatalogController
{


    public function create(Request $request, Response $response)
    {

        //Get Body Content Data
        $data = json_decode($request->body());

        //Get User Token
        $tokenValidate = false;
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $userToken = $_SERVER['HTTP_AUTHORIZATION'];
            $tokenValidate = TokenHelper::validateToken($userToken);
        }


        if ($tokenValidate) {


            //Request Validation
            $validateObject = CatalogRequest::getValidateObject($data);
            $validationErrors = ValidatorHelper::validate($validateObject);

            if (count($validationErrors) == 0) {

                //Filtering
                $payloads = [
                    'name' => ValidatorHelper::filter($data->name)
                ];

                //Create Catalog
                $catalogModel = new CatalogModel();
                $catalogModel->create($payloads);


                $catalogId = CatalogModel::lastInsertRecordId();
                $catalogData = [
                    'id' => $catalogId,
                    'name' => $data->name
                ];
                return Helper::successResponse($catalogData, "catalog created successfully");


            } else {
                //Return Failed Response
                return Helper::errorResponse($validationErrors, "Validation Errors");

            }

            //Return Failed Response
            return Helper::errorResponse([], "Catalog Cannot Created");
        }

        //Return Failed Response
        return Helper::errorResponse([], "Unauthorized Access");


    }


    public function update(Request $request, Response $response)
    {


        //Get Body Content Data
        $data = json_decode($request->body());

        //Get Category Id
        $data->id = $request->id;


        //Get User Token
        $tokenValidate = false;
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $userToken = $_SERVER['HTTP_AUTHORIZATION'];
            $tokenValidate = TokenHelper::validateToken($userToken);
        }


        if ($tokenValidate) {

            //Request Validation
            $validateObject = CatalogRequest::getValidateObject($data);
            $validationErrors = ValidatorHelper::validate($validateObject);

            if (count($validationErrors) == 0) {

                //Filtering
                $payloads = [
                    'id' => ValidatorHelper::filter($data->id),
                    'name' => ValidatorHelper::filter($data->name)
                ];

                //Create Catalog
                $catalogModel = new CatalogModel();
                $catalogModel->update($payloads);


                return Helper::successResponse($payloads, "catalog updated successfully");


            } else {
                //Return Failed Response
                return Helper::errorResponse($validationErrors, "Validation Errors");

            }

            //Return Failed Response
            return Helper::errorResponse([], "Catalog Cannot Updated");
        }

        //Return Failed Response
        return Helper::errorResponse([], "Unauthorized Access");


    }

    public function delete(Request $request, Response $response)
    {

        //Get Body Content Data
        $data = (object)[
            'id' => $request->id
        ];


        //Get User Token
        $tokenValidate = false;
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $userToken = $_SERVER['HTTP_AUTHORIZATION'];
            $tokenValidate = TokenHelper::validateToken($userToken);
        }


        if ($tokenValidate) {

            //Request Validation
            $validateObject = CatalogRequest::getValidateObject($data, 'delete');
            $validationErrors = ValidatorHelper::validate($validateObject);

            if (count($validationErrors) == 0) {

                //Filtering
                $payloads = [
                    'id' => ValidatorHelper::filter($data->id),

                ];

                //Create Catalog
                $catalogModel = new CatalogModel();

                $catalog = $catalogModel->get($payloads, 'id');

                if ($catalog) {
                    $catalogModel->delete($payloads);
                    return Helper::successResponse($payloads, "catalog deleted successfully");

                }


                return Helper::errorResponse([], "Catalog ID : (".$payloads['id'].") does not exsist");


            } else {
                //Return Failed Response
                return Helper::errorResponse($validationErrors, "Validation Errors");

            }


        }

        //Return Failed Response
        return Helper::errorResponse([], "Unauthorized Access");

    }

    public function getCatById(Request $request, Response $response)
    {

        //Get Body Content Data
        $data = (object)[
            'id' => $request->id
        ];


        //Get User Token
        $tokenValidate = false;
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $userToken = $_SERVER['HTTP_AUTHORIZATION'];
            $tokenValidate = TokenHelper::validateToken($userToken);
        }


        if ($tokenValidate) {

            //Request Validation
            $validateObject = CatalogRequest::getValidateObject($data, 'fetch');
            $validationErrors = ValidatorHelper::validate($validateObject);

            if (count($validationErrors) == 0) {

                //Filtering
                $payloads = [
                    'id' => ValidatorHelper::filter($data->id),

                ];

                //Create Catalog
                $catalogModel = new CatalogModel();
                $catalogData = $catalogModel->get($payloads, 'id');

                if ($catalogData) {
                    $payloads ['search_result'] = $catalogData;
                    return Helper::successResponse($payloads, "Catalog Data");
                }
                //Return Failed Response
                return Helper::errorResponse([], "Catalog data is not available");


            } else {
                //Return Failed Response
                return Helper::errorResponse($validationErrors, "Validation Errors");

            }


        } else {
            //Return Failed Response
            return Helper::errorResponse([], "Unauthorized Access");

        }


    }
}