<?php

namespace App\Http\Controllers;

use App\Models\OrganizationEmployee;
use App\Services\OrganizationService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function getAll(Request $request)
    {
        if (!Gate::check('super-user')) {
            return json_encode(['403' => "forbidden"]);
        }

        $orgs = Organization::all();

        return json_encode($orgs);
    }

    public function getOrgById(Request $request, $id)
    {
        $org = Organization::find($id);

        if (!Gate::check('read-update-delete-org', $org)) {
            return json_encode(['403' => "forbidden"]);
        }

        $org->employees;

        return json_encode(['data' => $org]);
    }

    public function createOrg(Request $request)
    {
        if (!Gate::check('create-org')) {
            return json_encode(['403' => "forbidden"]);
        }

        $org = new Organization();
        $org->fill($request->only(['name', 'ogrn', 'oktmo']));
        $org->admin_id = Auth::user()->id;
        $service = new OrganizationService();
        $rsp = $service->createOrg($org);

        return json_encode($rsp);
    }

    public function editOrg(Request $request, $id)
    {
        $org = Organization::find($id);

        if (!Gate::check('read-update-delete-org', $org)) {
            return json_encode(['403' => "forbidden"]);
        }

        $service = new OrganizationService();
        $org->fill($request->only(['name', 'ogrn', 'oktmo', 'admin_id']));
        $rsp = $service->updateOrg($org);

        return json_encode($rsp);
    }

    public function deleteOrgById($id)
    {
        $org = Organization::find($id);

        if (!Gate::check('read-update-delete-org', $org)) {
            return json_encode(['403' => "forbidden"]);
        }

        $isDeleteOrg = Organization::where('id', '=', $id)->delete();

        if ($isDeleteOrg = 1) {
            return json_encode('deleted successfully');
        }
    }

    public function deleteEmployeeFromOrg($org_id, $employee_id)
    {
        $org_employee = OrganizationEmployee::where([
            ['org_id', '=', $org_id],
            ['employee_id', '=', $employee_id]
        ])->delete();

        if ($org_employee = 1) {
            echo json_encode('success');
        }
    }
}
