<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
class OrganizationController extends Controller
{
    public function getAll() {
        // $org = new Organization;
        // dd($org->all());
        return view('organizations', ['data'=>Organization::all()]);
    }
}
