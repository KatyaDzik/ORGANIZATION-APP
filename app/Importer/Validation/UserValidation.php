<?php

namespace App\Importer\Validation;

use App\Http\Requests\UserPostRequest;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserValidation
{
    public function validateField( User $user)
    {
        $req = new UserPostRequest();
        $validator = Validator::make(
            [
                'firstname' => (string)$user->first_name,
                'middlename' => (string)$user->middle_name,
                'lastname' => (string)$user->last_name,
                'birthday' => (string)$user->birthday,
                'inn' => (string)$user->inn,
                'snils' => (string)$user->snils
            ],
            $req->rules()
        );

        if ($validator->fails()) {
            $messages = $validator->messages();
            $err[] = 'ФИО ' . $user->first_name . ' ' . $user->middle_name . ' ' . $user->last_name;
            $err[] = 'ИНН ' . $user->inn;
            $err[] = 'СНИЛС ' . (string)$user->snils;
            return ['msg_errors' => $messages, 'obj_err' => $err];
        }
        return ['success'];
    }

    public function isExist(User $user)
    {
        $exist_user_inn = User::where('inn', $user->inn)->first();
        if($exist_user_inn) {
            return ['msg_errors'=>['exist'=>['пользователь с таким ИИН уже существует']], 'obj' => $exist_user_inn];
        }

        $exist_user_snils = User::where('snils', $user->snils)->first();
        if($exist_user_snils) {
            return ['msg_errors'=>['exist'=>['пользователь с таким СНИЛС уже существует']], 'obj' => $exist_user_snils];
        }

        return  false;
    }
}
