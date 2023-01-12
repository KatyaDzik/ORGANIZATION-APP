<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserPostRequest;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function CreateUser(UserPostRequest $req, $id){

//        $validated = validator($req->all(), [
//            'firstname' => ['required', 'string', 'min:2', 'max:255', 'alpha'],
//            'middlename' => ['required', 'string', 'min:2', 'max:255', 'alpha'],
//            'lastname' => ['required', 'string', 'min:2', 'max:255', 'alpha'],
//
//
//        ])->validate();
        $validated = $req->validated();
        //dd($validated);
        //return redirect()->route()->withInput();
        $user = new User();
        $user->first_name = $req->input('firstname');
        $user->middle_name = $req->input('middlename');
        $user->last_name = $req->input('lastname');
        $user->birthday = $req->input('birthday');
        $user->snils = $req->input('snils');
        $user->inn = $req->input('inn');
        $user->org_id=$id;
        $user->save();
        return redirect()->route('org-data-by-id',  $id);
    }

    public function getUserById($id) {
        $user = new User();
        //$users = User::where('org_id', '=', $id)->get();
        //return view('user-profile', ['data'=>Organization::find($id), 'users'=>$user->where('org_id', '=', $id)->get()]);
        return view('user-profile');
    }
}
