<?php

namespace App\Services;

use App\Importer\Hash;
use App\Importer\Validation\EmployeeValidation;
use App\Models\Employee;
use App\Services\DBQueries;
use App\Services\FormDataService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmployeeService
{
    public function createEmployee(Employee $employee, $org_id)
    {
        $employee->hash = Hash::makeHashEmployee($employee);
        $validator_employee = new EmployeeValidation();
        $rsp = $validator_employee->validateField($employee);
        if(isset($rsp['msg_errors'])) {
            return $rsp;
        }

//        ПРОВЕРКА СУЩЕСТВОВАНИЯ ПОЛЬЗОВАТЕЛЯ С ТАКИМ ИНН
        $rsp = $validator_employee->isExist($employee);
        if(isset($rsp['msg_errors'])) {
            return $rsp;
        }
        $org = DBQueries::findOrgById($org_id);
        array_push($org->employee_list, $employee);
        try{
            DB::beginTransaction();
            DBQueries::upsertArrayEmployees([$employee->attributesToArray()]);
            $references = FormDataService::getReferences([$org]);
            DBQueries::insertArrayReferences($references);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('create employee from form '. $e);
        }
        return ['successful insert'];
    }

    public function updateEmployee(Employee $employee)
    {
        $employee->hash = Hash::makeHashEmployee($employee);
        $validator = new EmployeeValidation();
        $checkField = $validator->validateField($employee);
        if(isset($checkField['msg_errors'])) {
            return $checkField;
        }

        $checkExist = $validator->isExist($employee);
        if($checkExist) {
            if($checkExist['obj']->id != $employee->id) {
                return $checkExist;
            } else {
                DBQueries::upsertArrayEmployees([$employee->attributesToArray()]);
            }
        }
        return ['success'];
    }
}
