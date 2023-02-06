<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $this->authorize('super-user');
        $user_create = User::create($request->validated());

        return response()->json($user_create);
    }

    public function login(LoginRequest $request)
    {
//        if(!Auth::attempt($request->only('email', 'password'))){
//            return response([
//                'errors' => 'Неправильный логин или пароль'
//            ],Response::HTTP_UNAUTHORIZED);
//        }

//        $user = Auth::user();
//        $token = $user->createToken('token')->plainTextToken;
//
//        return response([
//            'jwt'=>$token
//        ]);
        $user = User::where('email' , $request->input('email'))->where( 'password' , $request->input('password'))->first();
        if($user){
            $success['jwt'] =  $user->createToken('token')->plainTextToken;
            return response()->json(['jwt' => $success['jwt']]);
        }else{
            return response()->json(['errors' => 'Неправильный логин или пароль'], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function logout()
    {
        auth()->user()->currentAccessToken()->delete();

        return response([
            'message' => 'Успешный выход'
        ]);
    }
}
