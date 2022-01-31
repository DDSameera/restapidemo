<?php

namespace App\Model;

class UserModel extends Model
{


    public function createUser($payload)
    {
        $sql = "INSERT INTO `db_users` (firstName,lastName,email,password,created_at,updated_at) VALUES (:firstName,:lastName,:email,:password,:created_at,:updated_at)";

        Parent::query($sql);
        Parent::bindParams('firstName', $payload['firstName']);
        Parent::bindParams('lastName', $payload['lastName']);
        Parent::bindParams('email', $payload['email']);
        Parent::bindParams('password', $payload['password']);
        Parent::bindParams('created_at', $payload['created_at']);
        Parent::bindParams('updated_at', $payload['updated_at']);

        return Parent::execute();


    }


    public function checkAlreadyExsist($fieldName, $fieldValue)
    {

        $sql = "SELECT * FROM `db_users` WHERE $fieldName= :$fieldName";
        parent::query($sql);
        parent::bindParams($fieldName, $fieldValue);
        parent::execute();
        return parent::fetch();

    }

    public function authenticate($email, $password)
    {
        $dbUser = $this->checkAlreadyExsist('email', $email);

        if ($dbUser) {
            $passwordVerify = password_verify($password, $dbUser['password']);
            return $passwordVerify;
        }
        return false;


    }


}