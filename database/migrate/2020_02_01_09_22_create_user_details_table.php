<?php

require_once __DIR__ . '/../../config/database.php';

use Config\Database;

function CreateUserDetailsTable()
{
    $database = Database::mysql();
    $create = $database->create("user_details", [
        "id" => [
            "BIGINT",
            "NOT NULL",
            "AUTO_INCREMENT",
            "PRIMARY KEY"
        ],
        "user_id" => [
            "BIGINT",
            "NOT NULL",
            "UNIQUE"
        ],
        "nama_lengkap" => [
            "VARCHAR(255)",
            "NOT NULL"
        ],
        "gender" => [
            "ENUM('pria','wanita')",
            "NOT NULL"
        ],
        "tanda_pengenal" => [
            "ENUM('ktp','sim','paspor')",
            "NOT NULL"
        ],
        "no_tanda_pengenal" => [
            "VARCHAR(20)",
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
        ],
        "FOREIGN KEY (<user_id>) REFERENCES users(<id>)"
    ]);
    if (!$create) {
        throw new Exception($database->error());
        exit;
    }
}
