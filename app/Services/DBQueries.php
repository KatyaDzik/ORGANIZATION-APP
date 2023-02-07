<?php

namespace App\Services;

use App\Models\Organization;
use App\Models\OrganizationEmployee;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DBQueries
{
    public static function insertData($inserted, $data)
    {
        try {
            DB::beginTransaction();
            self::upsertArrayOrgs($inserted['orgs']);
            self::upsertArrayEmployees($inserted['employees']);

            $org_employee_references = FormDataService::getReferences($data);
            self::insertArrayReferences($org_employee_references);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('import : ' . $e->getMessage());
        }
    }

    public static function upsertArrayOrgs($inserted)
    {
        try {
            Organization::upsert($inserted, ['id'], ['name', 'ogrn', 'oktmo']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Upsert organization : ' . $e->getMessage());
        }
    }

    public static function upsertArrayEmployees($inserted)
    {
        try {
            Employee::upsert($inserted, ['id'], ['first_name', 'middle_name', 'last_name', 'birthday', 'inn', 'snils']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Upsert employees : ' . $e->getMessage());
        }
    }

    public static function insertArrayReferences($org_employee_references)
    {
        try {
            OrganizationEmployee::insert($org_employee_references);
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

