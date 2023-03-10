<?php

namespace MVCFramework\Core;

use PDO;
use MVCFramework\App\Config;

abstract class Model
{

    /**
     * Get the PDO database connection
     *
     * @return PDO|null
     */
    protected static function getDB(): ?PDO
    {
        static $db = null;

        if ($db === null) {
            $type = 'mysql';
//            $server = 'localhost';
//            $dbname = 'mvc_framework';
            $port = '3307';
            $charset = 'utf8mb4';
//            $username = 'sig';
//            $password = '1234';

            try {
//                $db = new PDO("$type:host=$server;dbname=$dbname;port=$port;charset=$charset",
//                    $username, $password);
                $db = new PDO("$type:host=" . Config::DB_HOST . ";dbname=" . Config::DB_NAME . ";port=$port;charset=$charset",
                    Config::DB_USER, Config::DB_PASSWORD);

                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            } catch (\PDOException $e) {
                echo $e->getMessage();

            }
        }
        return $db;
    }
}