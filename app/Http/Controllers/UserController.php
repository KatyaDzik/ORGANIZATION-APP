<?php

namespace App\Http\Controllers;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Models\User;


class UserController extends Controller
{
    public function CreateUser(Request $req, $id)
    {
        $user = new User();
        $user->first_name = $req->input('firstname');
        $user->middle_name = $req->input('middlename');
        $user->last_name = $req->input('lastname');
        $user->birthday = $req->input('birthday');
        $user->snils = $req->input('snils');
        $user->inn = $req->input('inn');
        $service = new UserService();
        $rsp = $service->createUser($user, $id);
        return json_encode($rsp);
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
        $service = new UserService();
        $rsp = $service->updateUser($user);
        return json_encode($rsp);
    }

    public function getUserById($id) {
        $user = User::find($id);
        return view('user-profile', ['user'=>$user, 'organizations'=>$user->organizations]);
    }

    public function deleteUser($id)
    {
        $isDeleteUser = User::where('id', '=', $id)->delete();
        if($isDeleteUser = 1){
            echo json_encode('success');
        }
    }
}
