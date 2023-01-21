<?php

namespace App\Http\Controllers;

use App\Services\UpdateService;
use Illuminate\Http\Request;
use App\Models\Organization;
use App\Services\PostService;
use App\Http\Requests\OrgPutRequest;
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

    public function editOrg(Request $req, $id){
        $org= Organization::find($id);
        $org->name = $req->input('name');
        $org->ogrn = $req->input('ogrn');
        $org->oktmo = $req->input('oktmo');
        $service = new UpdateService();
        $rsp = $service->validateOrg($org);
        if(isset($rsp['msg_errors'])) {
            echo json_encode($rsp);
        } else {
            $rsp=$service->updateOrg($org);
            echo json_encode($rsp);
        }

    }
}
