<?php

namespace App\Importer\Validation;

use App\Http\Requests\OrgPostRequest;
use App\Models\Organization;
use App\Services\HashService;
use Illuminate\Support\Facades\Validator;

class OrganizationValidation
{
    public function validateField(Organization $org)
    {
        $req = new OrgPostRequest();
        $validator = Validator::make(
            [
                'name' => (string)$org->name,
                'ogrn' => (string)$org->ogrn,
                'oktmo' => (string)$org->oktmo
            ],
            $req->rules($org->id)
        );

        if ($validator->fails()) {
            $messages = $validator->messages();
            $err[] = 'Наименование '.(string)$org->name;
            $err[] = 'ОГРН '.(string)$org->ogrn;
            $err[] = 'ОКТМО '.(string)$org->oktmo;
            return ['msg_errors'=>$messages, 'obj_err'=>$err];
        }
        return ['success'];
    }

    public function isExist(Organization $org)
    {
        $exist_org_name = Organization::where('name', $org->name)->first();
        if($exist_org_name) {
            return ['msg_errors'=>['exist'=>['Организация с таким именем уже существует']], 'obj' => $exist_org_name];
        }

        $exist_org_ogrn = Organization::where('ogrn', $org->ogrn)->first();
        if($exist_org_ogrn) {
            return ['msg_errors'=>['exist'=>['Организация с таким ОГРН уже существует']], 'obj' => $exist_org_ogrn];
        }

        return  false;
    }
}
