<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserPostRequest;
use App\Models\OrganizationUser;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\PostService;

class UserController extends Controller
{
    public function CreateUser(UserPostRequest $req, $id){
        //$validated = $req->validated();
        $user = new User();
        $user->first_name = $req->input('firstname');
        $user->middle_name = $req->input('middlename');
        $user->last_name = $req->input('lastname');
        $user->birthday = $req->input('birthday');
        $user->snils = $req->input('snils');
        $user->inn = $req->input('inn');
        $service = new PostService();
        $rsp=$service->CreateUser($user);
        if($rsp[0]=='success'){
            $user->save();
            $org_user = new OrganizationUser();
            $org_user->org_id = $id;
            $org_user->user_id= $user->id;
            $org_user->save();
        }
        return redirect()->route('org-data-by-id',  $id);
    }

    public function getUserById($id) {
        $user = User::find($id);
        return view('user-profile', ['user'=>$user, 'organizations'=>$user->organizations]);
    }
}
