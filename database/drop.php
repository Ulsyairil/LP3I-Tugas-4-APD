<?php

namespace Database;

require_once __DIR__ . '/../config/database.php';

use Config\Database;
use Exception;

class Drop
{
    public static function drop()
    {
        try {
            $database = Database::mysql();
            $drop = [
                $database->drop('user_details'),
                $database->drop('users'),
            ];
            if (!$drop) {
                throw new Exception($database->error());
                exit;
            }
            echo "Success drop table";
        } catch (\Exception $error) {
            echo $error->getMessage();
        }
    }
}
