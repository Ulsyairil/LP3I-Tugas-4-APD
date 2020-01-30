<?php

namespace Api\AdminUser;

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/users.validation.php';

use Config\Database;
use Api\AdminUser\AdminUsersValidation;
use Exception;

class AdminUserService
{
    public static function show()
    {
        $database = Database::mysql();
        $data = $database->select("users", [
            "id", "username", "email", "nama_lengkap", "kartu_pengenal", "no_kartu", "created_at", "updated_at"
        ], [
            "deleted_at" => null,
            "ORDER" => [
                "created_at" => "DESC"
            ]
        ]);
        if ($data) {
            http_response_code(200);
            echo json_encode([
                'statusCode' => 200,
                'status' => 'success',
                'message' => 'success show users',
                'data' => $data
            ]);
        } else {
            http_response_code(200);
            echo json_encode([
                'statusCode' => 200,
                'status' => 'no data',
                'message' => 'no data found',
                'data' => []
            ]);
        }
    }

    public static function get($id)
    {
        $id = intval($id);
        $database = Database::mysql();
        $data = $database->select("users", [
            "id", "username", "email", "nama_lengkap", "kartu_pengenal", "no_kartu", "created_at", "updated_at"
        ], [
            "id" => $id,
            "deleted_at" => null,
        ]);
        if ($data) {
            http_response_code(200);
            echo json_encode([
                'statusCode' => 200,
                'status' => 'success',
                'message' => 'success get users',
                'data' => $data
            ]);
        } else {
            http_response_code(200);
            echo json_encode([
                'statusCode' => 200,
                'status' => 'not found',
                'message' => 'users not found'
            ]);
        }
    }

    public static function create($request)
    {
        AdminUsersValidation::validation($request);
        $database = Database::mysql();
        $insert = $database->insert("users", [
            "username" => $request['username'],
            "email" => $request['email'],
            "password" => password_hash($request['password'], PASSWORD_BCRYPT),
            "nama_lengkap" => $request['nama_lengkap'],
            "kartu_pengenal" => $request['kartu_pengenal'],
            "no_kartu" => $request['no_kartu'],
            "created_at" => date('Y-m-d H:i:s')
        ]);
        if ($insert) {
            $insertId = $database->id();
            $find = $database->select("users", [
                "id", "username", "email", "nama_lengkap", "kartu_pengenal", "no_kartu", "created_at",
            ], [
                "id" => intval($insertId)
            ]);
            http_response_code(200);
            echo json_encode([
                'statusCode' => 200,
                'status' => 'success',
                'message' => 'success create user',
                'data' => $find
            ]);
        } else {
            throw new Exception($database->error());
        }
    }

    public static function update($id, $request)
    {
        AdminUsersValidation::validation($request);
        $id = intval($id);
        $database = Database::mysql();
        $find = $database->count("users", [
            "id" => $id,
            "deleted_at" => null,
        ]);
        if ($find > 0) {
            $update = $database->update("users", [
                "username" => $request['username'],
                "email" => $request['email'],
                "password" => password_hash($request['password'], PASSWORD_BCRYPT),
                "nama_lengkap" => $request['nama_lengkap'],
                "kartu_pengenal" => $request['kartu_pengenal'],
                "no_kartu" => $request['no_kartu'],
                "updated_at" => date('Y-m-d H:i:s')
            ], [
                "id" => $id
            ]);
            if (!$update) {
                throw new Exception($database->error());
            }
            $find = $database->select("users", [
                "id", "username", "email", "nama_lengkap", "kartu_pengenal", "no_kartu", "created_at", "updated_at"
            ], [
                "id" => $id
            ]);
            http_response_code(200);
            echo json_encode([
                'statusCode' => 200,
                'status' => 'success',
                'message' => 'success update user',
                'data' => $find
            ]);
        } else {
            http_response_code(200);
            echo json_encode([
                'statusCode' => 200,
                'status' => 'not found',
                'message' => 'users not found'
            ]);
        }
    }

    public static function delete($id)
    {
        $id = intval($id);
        $database = Database::mysql();
        $find = $database->count("users", [
            "id" => $id,
            "deleted_at" => null,
        ]);
        if ($find > 0) {
            $update = $database->update("users", [
                "deleted_at" => date('Y-m-d H:i:s')
            ], [
                "id" => $id
            ]);
            if (!$update) {
                throw new Exception($database->error());
            }
            http_response_code(200);
            echo json_encode([
                'statusCode' => 200,
                'status' => 'success',
                'message' => 'success delete user'
            ]);
        } else {
            http_response_code(200);
            echo json_encode([
                'statusCode' => 200,
                'status' => 'not found',
                'message' => 'users not found'
            ]);
        }
    }
}
