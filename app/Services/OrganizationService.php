<?php

namespace App\Services;

use App\Importer\Hash;
use App\Importer\Validation\OrganizationValidation;
use App\Models\Organization;
use App\Services\DBQueries;

class OrganizationService
{
    public function createOrg(Organization $org)
    {
        $org->hash = Hash::makeHashOrg($org);
        $validator_org = new OrganizationValidation();
        $rsp = $validator_org->validateField($org);
        if(isset($rsp['msg_errors'])) {
            return $rsp;
        }
        $rsp = $validator_org->isExist($org);
        if(isset($rsp['msg_errors'])) {
            return $rsp;
        }
        DBQueries::upsertArrayOrgs([$org->attributesToArray()]);
        return ['success'];
    }

    public function updateOrg(Organization $org)
    {
        $org->hash = Hash::makeHashOrg($org);
        $validator_org = new OrganizationValidation();
        $rsp = $validator_org->validateField($org);
        if(isset($rsp['msg_errors'])) {
            return $rsp;
        }
        $rsp = $validator_org->isExist($org);
        if($rsp){
            if($rsp['obj']->id != $org->id) {
                return $rsp;
            } else {
                DBQueries::upsertArrayOrgs([$org->attributesToArray()]);
            }
        }
        return ['success'];
    }
}
