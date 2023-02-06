<?php

namespace App\Importer\Validation;

use App\Http\Requests\EmployeePostRequest;
use App\Models\Employee;
use Illuminate\Support\Facades\Validator;

class EmployeeValidation
{
    public function validateField(Employee $employee)
    {
        $req = new EmployeePostRequest();
        $validator = Validator::make(
            [
                'firstname' => (string)$employee->first_name,
                'middlename' => (string)$employee->middle_name,
                'lastname' => (string)$employee->last_name,
                'birthday' => (string)$employee->birthday,
                'inn' => (string)$employee->inn,
                'snils' => (string)$employee->snils
            ],
            $req->rules()
        );

        if ($validator->fails()) {
            $messages = $validator->messages();
            $err[] = 'ФИО ' . $employee->first_name . ' ' . $employee->middle_name . ' ' . $employee->last_name;
            $err[] = 'ИНН ' . $employee->inn;
            $err[] = 'СНИЛС ' . (string)$employee->snils;
            return ['msg_errors' => $messages, 'obj_err' => $err];
        }

        return ['success'];
    }

    public function isExist(Employee $employee)
    {
        $exist_employee_inn = Employee::where('inn', $employee->inn)->first();
        if($exist_employee_inn) {
            return ['msg_errors'=>['exist'=>['пользователь с таким ИИН уже существует']], 'obj' => $exist_employee_inn];
        }

        $exist_employee_snils = Employee::where('snils', $employee->snils)->first();
        if($exist_employee_snils) {
            return ['msg_errors'=>['exist'=>['пользователь с таким СНИЛС уже существует']], 'obj' => $exist_employee_snils];
        }

        return  false;
    }
}
