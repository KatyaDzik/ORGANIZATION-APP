<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\User;
use App\Http\Requests\UserPostRequest;
use App\Http\Requests\OrgPostRequest;
use Illuminate\Support\Facades\Validator;use function Symfony\Component\String\u;

class PostService
{
    public function validateUser(User $user)
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

    public function validateOrg(Organization $organization){
        $req = new OrgPostRequest();
        $validator = Validator::make(
            [
                'name' => (string)$organization->name,
                'ogrn' => (string)$organization->ogrn,
                'oktmo' => (string)$organization->oktmo
            ],
            $req->rules()
        );

        if ($validator->fails()) {
            $messages = $validator->messages();
            $err[] = 'Наименование '.(string)$organization->name;
            $err[] = 'ОГРН '.(string)$organization->ogrn;
            $err[] = 'ОКТМО '.(string)$organization->oktmo;
            return ['msg_errors'=>$messages, 'obj_err'=>$err];
        }
        return ['success'];
    }

    public function createOrg(Organization $organization)
    {
        $saved=$organization->save();
        return ['Успешно обновлено'];
    }
}


//РАзбить на парсер импортер , добавить логирование ошибок, красиво назвать переменные org1(?), разбить на функции xml, update user org
// сделать чтобы у одного пользователя было мно
