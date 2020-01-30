<?php

namespace Database;

require_once __DIR__ . '/migrate/2020_01_28_12_16_create_users_table.php';

class Migrate
{
    public static function migrate()
    {
        try {
            CreateUsersTable();
            echo "Success migrate table";
        } catch (\Exception $error) {
            echo $error->getMessage();
        }
    }
}
