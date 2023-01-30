<?php

namespace App\Services;

use App\Importer\Hash;
use App\Importer\Validation\OrganizationValidation;
use App\Importer\Validation\UserValidation;
use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\User;

class FormDataService
{
    public function getOrgs($data){
        $orgs = [];
        $getOrgs = $this->generator($data, "findOrgForUpsert");
        if ($getOrgs) {
            foreach ($getOrgs as $item) {
                if ($item) {
                    array_push($orgs, $item);
                }
            }
        }
        return $orgs;
    }

    public function getUsers($data){
        $users = [];
        foreach ($data as $org) {
            $getUsers = $this->generator($org->user_list, "findUserForUpsert");
            if ($getUsers) {
                foreach ($getUsers as $item) {
                    if ($item) {
                        array_push($users, $item);
                    }
                }
            }
        }
        return $users;
    }

    public static function getReferences($data)
    {
        $org_user_references = [];
        foreach ($data as $org) {
            foreach ($org->user_list as $user) {
                $relation  = FormDataService::findExistReference(['ogrn'=>$org->ogrn, 'inn'=>$user->inn]);
                if ($relation){
                    array_push($org_user_references, $relation);
                }
            }
        }
        return $org_user_references;
    }


    public static function findOrgForUpsert(Organization $org)
    {
        $validator_org = new OrganizationValidation();
        $org->hash = Hash::makeHashOrg($org);
        //Ищем в БД организацию с такими значениями
        $rsp = $validator_org->isExist($org);
        if ($rsp) {
            //Сравниваем кеш
            if ($rsp['obj']->hash != $org->hash) {
                return $org->attributesToArray();
            }
        } else {
            return $org->attributesToArray();
        }
    }

    public static function findUserForUpsert(User $user)
    {
        $validator_user = new UserValidation();
        $user->hash = Hash::makeHashUser($user);
        $rsp = $validator_user->isExist($user);
        if ($rsp) {
            //Сравниваем кеш
            if ($rsp['obj']->hash != $user->hash) {
                return $user->attributesToArray();
            }
        } else {
            return $user->attributesToArray();
        }
    }

    public static function findExistReference($data)
    {
        $orgs_user = OrganizationUser::join('users', 'organization_users.user_id', '=', 'users.id')
            ->join('organizations', 'organization_users.org_id', '=', 'organizations.id')
            ->where([['users.inn', '=', $data['inn']],['organizations.ogrn', '=', $data['ogrn']]])
            ->first();

        if(!$orgs_user) {
            $user_by_inn = User::where('inn', '=', $data['inn'])->first();
            $org_by_ogrn = Organization::where('ogrn', '=', $data['ogrn'])->first();
            $org_user = new OrganizationUser();
            $org_user->user_id = $user_by_inn->id;
            $org_user->org_id = $org_by_ogrn->id;
            return $org_user->attributesToArray();
        }
    }

    public function generator($arr, $callback)
    {
        foreach ($arr as $item) {
            yield self::$callback($item);
        }
    }
}
