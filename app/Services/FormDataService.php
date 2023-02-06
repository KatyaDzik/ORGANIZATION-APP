<?php

namespace App\Services;

use App\Importer\Hash;
use App\Importer\Validation\OrganizationValidation;
use App\Importer\Validation\EmployeeValidation;
use App\Models\Organization;
use App\Models\OrganizationEmployee;
use App\Models\Employee;

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

    public function getEmployees($data){
        $employees = [];
        foreach ($data as $org) {
            $getEmployees = $this->generator($org->employee_list, "findEmployeeForUpsert");
            if ($getEmployees) {
                foreach ($getEmployees as $item) {
                    if ($item) {
                        array_push($employees, $item);
                    }
                }
            }
        }
        return $employees;
    }

    public static function getReferences($data)
    {
        $org_employee_references = [];
        foreach ($data as $org) {
            foreach ($org->employee_list as $employee) {
                $relation  = FormDataService::findExistReference(['ogrn'=>$org->ogrn, 'inn'=>$employee->inn]);
                if ($relation){
                    array_push($org_employee_references, $relation);
                }
            }
        }

        return $org_employee_references;
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

    public static function findEmployeeForUpsert(Employee $employee)
    {
        $validator_employee = new EmployeeValidation();
        $employee->hash = Hash::makeHashEmployee($employee);
        $rsp = $validator_employee->isExist($employee);
        if ($rsp) {
            //Сравниваем кеш
            if ($rsp['obj']->hash != $employee->hash) {
                return $employee->attributesToArray();
            }
        } else {
            return $employee->attributesToArray();
        }
    }

    public static function findExistReference($data)
    {
        $orgs_employee = OrganizationEmployee::join('employees', 'organization_employees.employee_id', '=', 'employees.id')
            ->join('organizations', 'organization_employees.org_id', '=', 'organizations.id')
            ->where([['employees.inn', '=', $data['inn']],['organizations.ogrn', '=', $data['ogrn']]])
            ->first();

        if(!$orgs_employee) {
            $employee_by_inn = Employee::where('inn', '=', $data['inn'])->first();
            $org_by_ogrn = Organization::where('ogrn', '=', $data['ogrn'])->first();
            $org_employee = new OrganizationEmployee();
            $org_employee->employee_id = $employee_by_inn->id;
            $org_employee->org_id = $org_by_ogrn->id;
            return $org_employee->attributesToArray();
        }
    }

    public function generator($arr, $callback)
    {
        foreach ($arr as $item) {
            yield self::$callback($item);
        }
    }
}
