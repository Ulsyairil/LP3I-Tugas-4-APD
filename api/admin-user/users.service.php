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
            "[>]user_details" => ["id" => "user_id"]
        ], [
            'users.id',
            'users.username',
            'users.email',
            'users.created_at',
            'users.updated_at',
            'detail' => [
                'user_details.nama_lengkap',
                'user_details.gender',
                'user_details.tanda_pengenal',
                'user_details.no_tanda_pengenal',
                'user_details.created_at',
                'user_details.updated_at'
            ]
        ], [
            "users.deleted_at" => null,
            "ORDER" => [
                "users.created_at" => "DESC"
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
            "[>]user_details" => ["id" => "user_id"]
        ], [
            'users.id',
            'users.username',
            'users.email',
            'users.created_at',
            'users.updated_at',
            'detail' => [
                'user_details.nama_lengkap',
                'user_details.gender',
                'user_details.tanda_pengenal',
                'user_details.no_tanda_pengenal',
                'user_details.created_at',
                'user_details.updated_at'
            ]
        ], [
            "users.id" => $id,
            "users.deleted_at" => null,
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
        $insertuser = $database->insert("users", [
            "username" => $request['username'],
            "email" => $request['email'],
            "password" => password_hash($request['password'], PASSWORD_BCRYPT),
            "created_at" => date('Y-m-d H:i:s')
        ]);
        if ($insertuser) {
            $insertId = $database->id();
            $insertdetail = $database->insert('user_details', [
                'user_id' => $insertId,
                'nama_lengkap' => $request['nama_lengkap'],
                'gender' => $request['gender'],
                'tanda_pengenal' => $request['tanda_pengenal'],
                'no_tanda_pengenal' => $request['no_tanda_pengenal'],
                'created_at' => date('Y-m-d H:i:s')
            ]);
            if (!$insertdetail) {
                throw new Exception($database->error());
                exit;
            }
            $find = $database->select("users", [
                "[>]user_details" => ["id" => "user_id"]
            ], [
                'users.id',
                'users.username',
                'users.email',
                'users.created_at',
                'detail' => [
                    'user_details.nama_lengkap',
                    'user_details.gender',
                    'user_details.tanda_pengenal',
                    'user_details.no_tanda_pengenal',
                    'user_details.created_at'
                ]
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
            $updateuser = $database->update("users", [
                "username" => $request['username'],
                "email" => $request['email'],
                "password" => password_hash($request['password'], PASSWORD_BCRYPT),
                "updated_at" => date('Y-m-d H:i:s')
            ], [
                "id" => $id
            ]);
            if (!$updateuser) {
                throw new Exception($database->error());
            }
            $updatedetail = $database->update("user_details", [
                'nama_lengkap' => $request['nama_lengkap'],
                'gender' => $request['gender'],
                'tanda_pengenal' => $request['tanda_pengenal'],
                'no_tanda_pengenal' => $request['no_tanda_pengenal'],
                'updated_at' => date('Y-m-d H:i:s')
            ], [
                "user_id" => $id
            ]);
            if (!$updatedetail) {
                throw new Exception($database->error());
            }
            $find = $database->select("users", [
                "[>]user_details" => ["id" => "user_id"]
            ], [
                'users.id',
                'users.username',
                'users.email',
                'users.created_at',
                'users.updated_at',
                'detail' => [
                    'user_details.nama_lengkap',
                    'user_details.gender',
                    'user_details.tanda_pengenal',
                    'user_details.no_tanda_pengenal',
                    'user_details.created_at',
                    'user_details.updated_at'
                ]
            ], [
                "users.id" => $id
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
            $update = $database->update("user_details", [
                "deleted_at" => date('Y-m-d H:i:s')
            ], [
                "user_id" => $id
            ]);
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
