<?php

require_once __DIR__ . '/../../config/database.php';

use Config\Database;

function CreateUsersTable()
{
    $database = Database::mysql();
    $create = $database->create("users", [
        "id" => [
            "BIGINT",
            "NOT NULL",
            "AUTO_INCREMENT",
            "PRIMARY KEY"
        ],
        "username" => [
            "VARCHAR(50)",
            "NOT NULL",
            "UNIQUE"
        ],
        "email" => [
            "VARCHAR(255)",
            "NOT NULL",
            "UNIQUE"
        ],
        "password" => [
            "TEXT",
            "NOT NULL"
        ],
        "created_at" => [
            "DATETIME",
            "NOT NULL"
        ],
        "updated_at" => [
            "DATETIME"
        ],
        "deleted_at" => [
            "DATETIME"
        ]
    ]);
    if (!$create) {
        throw new Exception($database->error());
        exit;
    }
}
