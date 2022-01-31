<?php

namespace App\Model;

class CatalogModel extends Model
{

    public function create($payloads)
    {

        $sql = "INSERT INTO `db_catalogs` (name) VALUES (:name)";

        Parent::query($sql);
        Parent::bindParams('name', $payloads['name']);
        Parent::execute();
    }

    public function update($payloads)
    {

        $sql = "UPDATE `db_catalogs` SET name=:name WHERE id=:id";

        Parent::query($sql);
        Parent::bindParams('name', $payloads['name']);
        Parent::bindParams('id', $payloads['id']);
        Parent::execute();
    }

    public function delete($payloads)
    {

        $sql = "DELETE FROM `db_catalogs`  WHERE id=:id";

        Parent::query($sql);
        Parent::bindParams('id', $payloads['id']);
       return Parent::execute();

    }

    public function get($payloads, $field)
    {

        $sql = "SELECT *  FROM `db_catalogs`  WHERE $field=:$field";

        Parent::query($sql);
        Parent::bindParams('id', $payloads[$field]);
        Parent::execute();
        return Parent::fetch();
    }


}