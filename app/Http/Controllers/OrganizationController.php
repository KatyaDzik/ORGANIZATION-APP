<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use App\Models\User;
class OrganizationController extends Controller
{
    public function getAll(Request $req) {
        $validated = $req->validate([
           'page' => ['nullable', 'integer', 'min:1', 'max:100']
        ]);
        $page = $validated['page'] ?? 1;
        $orgs = Organization::query()->paginate(10);
        return view('organizations', compact('orgs'));
    }

    public function getOrgById(Request $req, $id) {
        $user = new User();
        return view('org-profile', ['data'=>Organization::find($id), 'users'=>$user->where('org_id', '=', $id)->get()]);

    }
}
