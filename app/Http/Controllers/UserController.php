<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserPostRequest;
use App\Models\OrganizationUser;
use App\Services\PutService;
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
        $rsp=$service->validateUser($user);
        if($rsp[0]=='success'){
            $user->save();
            $org_user = new OrganizationUser();
            $org_user->org_id = $id;
            $org_user->user_id= $user->id;
            $org_user->save();
        }
        return redirect()->route('org-data-by-id',  $id);
    }

    public function editUser(Request $req, $id)
    {
        $user= User::find($id);
        $user->first_name = $req->input('first_name');
        $user->last_name = $req->input('last_name');
        $user->middle_name = $req->input('middle_name');
        $user->birthday = $req->input('birthday');
        $user->inn = $req->input('inn');
        $user->snils = $req->input('snils');
        $service = new PutService();
        $rsp = $service->validateUser($user);
        if(isset($rsp['msg_errors'])) {
            echo json_encode($rsp);
        } else {
            $rsp=$service->updateUser($user);
            echo json_encode($rsp);
        }
    }


    public function getUserById($id) {
        $user = User::find($id);
        return view('user-profile', ['user'=>$user, 'organizations'=>$user->organizations]);
    }

    public function deleteUser($id)
    {
        $user = User::where('id', '=', $id)->delete();
        if($user = 1){
            echo json_encode('success');
        }
    }
}
