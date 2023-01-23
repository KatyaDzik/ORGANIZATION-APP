<?php

namespace App\Services;

use App\Http\Requests\OrgPutRequest;
use App\Http\Requests\UserPutRequest;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class PutService
{
    public function validateOrg(Organization $organization)
    {
        $req = new OrgPutRequest();
        $validator = Validator::make(
            [
                'name' => (string)$organization->name,
                'ogrn' => (string)$organization->ogrn,
                'oktmo' => (string)$organization->oktmo
            ],
            $req->rules($organization->id)
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

    public function updateOrg(Organization $organization)
    {
        $organization->save();
        return ['Успешно обновлено'];
    }

    public function validateUser(User $user)
    {
        $req = new UserPutRequest();
        $validator = Validator::make(
            [
                'firstname' => (string)$user->first_name,
                'middlename' => (string)$user->middle_name,
                'lastname' => (string)$user->last_name,
                'birthday' => (string)$user->birthday,
                'inn' => (string)$user->inn,
                'snils' => (string)$user->snils
            ],
            $req->rules($user->id)
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

    public function updateUser(User $user)
    {
        $user->save();
        return ['Успешно обновлено'];
    }
}
