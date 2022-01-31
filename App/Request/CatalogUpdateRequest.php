<?php

namespace App\Request;

class CatalogUpdateRequest
{

    public static function getValidateObject($data)
    {

        return [
            (object)[
                'key' => 'id',
                'data' => $data->id,
                'validator' => [
                    'required'
                ]

            ]
            ,
            (object)[
                'key' => 'name',
                'data' => $data->name,
                'validator' => [
                    'required'
                ]

            ]
        ];
    }

}