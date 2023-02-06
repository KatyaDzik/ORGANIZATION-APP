<?php

namespace App\Http\Controllers;
use App\Models\Organization;
use App\Services\EmployeeService;
use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Gate;


class EmployeeController extends Controller
{
    public function сreateEmployee(Request $req, $id)
    {
        $org= Organization::find($id);

        if(!Gate::check('read-update-delete-org', $org)){
            return json_encode(['403'=>"forbidden"]);
        }

        $employee = new Employee();
        $employee->first_name = $req->input('first_name');
        $employee->middle_name = $req->input('middle_name');
        $employee->last_name = $req->input('last_name');
        $employee->birthday = $req->input('birthday');
        $employee->snils = $req->input('snils');
        $employee->inn = $req->input('inn');
        $service = new EmployeeService();
        $rsp = $service->createEmployee($employee, $id);

        return json_encode($rsp);
    }

    public function editEmployee(Request $request, $org_id, $employee_id)
    {
        if(!Gate::check('read-update-delete-org', Organization::find($org_id))){
            return json_encode(['403'=>"forbidden"]);
        }
        $employee= Employee::find($employee_id);
        $employee->fill($request->only(['first_name', 'middle_name', 'last_name', 'birthday', 'inn', 'snils']));
        $service = new EmployeeService();
        $rsp = $service->updateEmployee($employee);
        return json_encode($rsp);
    }

    public function getEmployeeById($org_id, $employee_id) {
        if(!Gate::check('read-update-delete-org', Organization::find($org_id))){
            return json_encode(['403'=>"forbidden"]);
        }
        $employee = Employee::find($employee_id);
        $employee->organizations;
        return $employee;
        //return view('employee-profile', ['employee'=>$employee, 'organizations'=>$employee->organizations]);
    }

    public function deleteEmployee($org_id, $employee_id)
    {
        if(!Gate::check('read-update-delete-org', Organization::find($org_id))){
            return json_encode(['403'=>"forbidden"]);
        }
        $isDeleteEmployee = Employee::where('id', '=', $employee_id)->delete();
        if($isDeleteEmployee = 1){
            echo json_encode('deleted successfully');
        }
    }
}
