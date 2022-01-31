<?php

namespace App\Request;

class CatalogRequest
{

    public static function getValidateObject($data,$type="")
    {

        if ($type=="delete" || $type=="fetch") {
            return [
                (object)[
                    'key' => 'id',
                    'data' => $data->id,
                    'validator' => [
                        'required'
                    ]

                ]
            ];
        }

        return [
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