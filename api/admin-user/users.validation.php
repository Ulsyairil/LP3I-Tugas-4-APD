<?php

namespace Api\AdminUser;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../helpers/unique.helpers.php';

use Exception;
use Rakit\Validation\Validator;
use UniqueRule;

class AdminUsersValidation
{
    public static function validation($request)
    {
        $validator = new Validator;
        $validator->addValidator('unique', new UniqueRule());
        $rules  = [
            'username' => 'required|max:50|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'nama_lengkap' => 'required|max:255',
            'kartu_pengenal' => 'required|in:ktp,sim,paspor',
            'no_kartu' => 'required|numeric|unique:users,no_kartu'
        ];
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'POST':
                $rules = $rules;
                break;

            case 'PUT':
                $rules = [];
                $rules['username'] = 'required|max:50';
                $rules['email'] = 'required|email|max:255';
                $rules['no_kartu'] = 'required|numeric';
                break;

            default:
                $rules = $rules;
                break;
        }
        $validation = $validator->make($request, $rules);
        $validation->validate();

        if ($validation->fails()) {
            $errors = $validation->errors();
            http_response_code(400);
            echo json_encode([
                'statusCode' => 400,
                'status' => 'entity error',
                'message' => $errors->firstOfAll()
            ]);
            exit;
        }
    }
}
