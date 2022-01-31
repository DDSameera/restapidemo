<?php

namespace App\Model;

use Exception;
use PDO;

/**
 *
 */
class Model
{


    protected static $dbHost = "localhost";
    protected static $dbUsername = "root";
    protected static $dbPassword = "";
    protected static $statement = "";
    protected static $dbName = "php_rest_api";
    protected static $dbConnection;

    /**
     * Create Database Connection
     */
    public function __construct()
    {
        //Create Data Source Name (DSN)
        $dsn = "mysql:host=" . Self::$dbHost . ";dbname=" . Self::$dbName . ";charset=UTF8";

        try {
            // Create PDO Object
            $options = [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];

            Self::$dbConnection = new PDO($dsn, Self::$dbUsername, Self::$dbPassword, $options);


        } catch (Exception $exception) {
            return (object)[
                'status' => 500,
                'data' => $exception->getCode(),
                'message' => $exception->getMessage()
            ];

        }
    }


    /**
     * Create Prepare Statement
     * @param $query
     */
    public static function query($query)
    {
        Self::$statement = Self::$dbConnection->prepare($query);
    }


    public static function bindParams(string $param, string $value, mixed $type = null)
    {

        $type = null;

        switch (true) {
            case is_bool($value) :
                $type = PDO::PARAM_BOOL;
                break;

            case is_int($value):
                $type = PDO::PARAM_INT;
                break;

            case is_null($value):
                $type = PDO::PARAM_NULL;
                break;


            default :
                $type = PDO::PARAM_STR;
                break;

        }


        Self::$statement->bindValue($param, $value, $type);
    }


    /**
     * Execute SQL Statement
     * @param void
     * @return bool
     */
    public static function execute(): bool
    {
        return Self::$statement->execute();
    }

    /**
     * @return int
     */
    public static function lastInsertRecordId () : int {
        return Self::$dbConnection->lastInsertId();
    }

    public static function fetch(){


        return Self::$statement->fetch(PDO::FETCH_ASSOC);

    }




}