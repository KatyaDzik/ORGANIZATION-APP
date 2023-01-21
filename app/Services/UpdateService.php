<?php

namespace App\Services;

use App\Http\Requests\OrgPostRequest;
use App\Http\Requests\OrgPutRequest;
use App\Models\Organization;
use Illuminate\Support\Facades\Validator;

class UpdateService
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
}
