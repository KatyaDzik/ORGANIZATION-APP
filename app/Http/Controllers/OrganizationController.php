<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\User;
class OrganizationController extends Controller
{
    public function getAll() {
        // $org = new Organization;
        // dd($org->all());
        return view('organizations', ['data'=>Organization::all()]);
    }

    public function getOrgById($id) {
        $user = new User();
        //$users = User::where('org_id', '=', $id)->get();
        return view('org-profile', ['data'=>Organization::find($id), 'users'=>$user->where('org_id', '=', $id)->get()]);
    }
}
