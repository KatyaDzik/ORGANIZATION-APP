<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\OrganizationUser;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DBQueries
{
    public static function insertData($inserted, $data)
    {
        try{
            DB::beginTransaction();
            self::upsertArrayOrgs($inserted['orgs']);
            self::upsertArrayUsers($inserted['users']);

            $org_user_references = FormDataService::getReferences($data);
            self::insertArrayReferences($org_user_references);
            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('import : '. $e->getMessage());
        }
    }

    public static function upsertArrayOrgs($inserted)
    {
        try {
            Organization::upsert($inserted, ['ogrn'], ['name', 'oktmo']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Upsert organization : ' . $e->getMessage());
        }
    }

    public static function upsertArrayUsers($inserted)
    {
        try {
            User::upsert ($inserted, ['inn'], ['first_name', 'middle_name', 'last_name', 'birthday', 'snils']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Upsert users : ' . $e->getMessage());
        }
    }

    public static function insertArrayReferences($org_user_references)
    {
        try {
            OrganizationUser::insert($org_user_references);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Insert relationships : ' . $e->getMessage());
        }
    }

    public static function findOrgById($id)
    {
        return Organization::find($id);
    }
}

