<?php

namespace App\Http\Controllers;

use App\Models\OrganizationUser;
use App\Services\OrganizationService;
use Illuminate\Http\Request;
use App\Models\Organization;
class OrganizationController extends Controller
{
    public function getAll(Request $req)
    {
        $validated = $req->validate([
           'page' => ['nullable', 'integer', 'min:1', 'max:100']
        ]);
        $page = $validated['page'] ?? 1;
        $orgs = Organization::query()->paginate(10);
        return view('organizations', compact('orgs'));
    }

    public function getOrgById(Request $req, $id)
    {
        $org = Organization::find($id);
        return view('org-profile', ['data'=>$org, 'users'=>$org->users]);
    }

    public function createOrg(Request $req)
    {
        $org= new Organization();
        $org->name = $req->input('name');
        $org->ogrn = $req->input('ogrn');
        $org->oktmo = $req->input('oktmo');
        $service = new OrganizationService();
        $rsp = $service->createOrg($org);
        return json_encode($rsp);
    }

    public function editOrg(Request $req, $id)
    {
        $org= Organization::find($id);
        $org->name = $req->input('name');
        $org->ogrn = $req->input('ogrn');
        $org->oktmo = $req->input('oktmo');
        $service = new OrganizationService();
        $rsp = $service->updateOrg($org);
        return json_encode($rsp);
    }

    public function deleteOrgById($id)
    {
        $isDeleteOrg = Organization::where('id', '=', $id)->delete();
        if($isDeleteOrg = 1){
            echo json_encode('success');
        }
    }

    public function deleteUserFromOrg($org_id, $user_id)
    {
        $org_user = OrganizationUser::where([['org_id', '=', $org_id],['user_id', '=', $user_id]])->delete();
        if($org_user = 1){
            echo json_encode('success');
        }
    }


}
