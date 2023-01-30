<?php

namespace App\Services;

use App\Importer\Hash;
use App\Importer\Validation\UserValidation;
use App\Models\User;
use App\Services\DBQueries;
use App\Services\FormDataService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserService
{
    public function createUser(User $user, $org_id)
    {
        $user->hash = Hash::makeHashUser($user);
        $validator_user = new UserValidation();
        $rsp = $validator_user->validateField($user);
        if(isset($rsp['msg_errors'])) {
            return $rsp;
        }

        $rsp = $validator_user->isExist($user);
        if(isset($rsp['msg_errors'])) {
            return $rsp;
        }
        $org = DBQueries::findOrgById($org_id);
        array_push($org->user_list, $user);
        try{
            DB::beginTransaction();
            DBQueries::upsertArrayUsers([$user->attributesToArray()]);
            $references = FormDataService::getReferences([$org]);
            DBQueries::insertArrayReferences($references);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('create user from form '. $e);
        }
        return 'success';
    }
    public function updateUser(User $user)
    {
        $user->hash = Hash::makeHashUser($user);
        $validator = new UserValidation();
        $checkField = $validator->validateField($user);
        if(isset($checkField['msg_errors'])) {
            return $checkField;
        }

        $checkExist = $validator->isExist($user);
        if($checkExist) {
            if($checkExist['obj']->id != $user->id) {
                return $checkExist;
            } else {
                DBQueries::upsertArrayUsers([$user->attributesToArray()]);
            }
        }
        return ['success'];
    }
}
