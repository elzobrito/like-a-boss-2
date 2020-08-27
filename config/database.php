<?php

namespace Config;

use elzobrito\ADatabase;

class Database extends ADatabase
{
    //put your code here

    public static function getDB($driver = null)
    {
        $db = null;
        switch ($driver) {
            case 'mysql':
                $db = [
                    'host' => '',
                    'port' => '',
                    'database' => '',
                    'user' => '',
                    'password' => '',
                    'driver' => ''
                ];
                break;
            default:
                $db = [
                    'host' => '',
                    'port' => '',
                    'database' => '',
                    'user' => '',
                    'password' => '',
                    'driver' => ''
                ];
                break;
        }
        return $db;
    }
}
