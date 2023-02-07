<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
// ВХОД, ВЫХОД, РЕГИСТРАЦИЯ
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->middleware('auth:sanctum');;
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->middleware('auth:sanctum');

// CRUD ОРГАНИЗАЦИИ
Route::get('organization/{id}',
    [\App\Http\Controllers\OrganizationController::class, 'getOrgById'])->middleware('auth:sanctum');
Route::post('organization',
    [\App\Http\Controllers\OrganizationController::class, 'createOrg'])->middleware('auth:sanctum');
Route::put('organization/{id}',
    [\App\Http\Controllers\OrganizationController::class, 'editOrg'])->middleware('auth:sanctum');
Route::patch('organization/{id}',
    [\App\Http\Controllers\OrganizationController::class, 'editOrg'])->middleware('auth:sanctum');
Route::delete('organization/{id}',
    [\App\Http\Controllers\OrganizationController::class, 'deleteOrgById'])->middleware('auth:sanctum');

// CRUD СОТРУДНИКОВ
Route::get('organization/{org_id}/employee/{employee_id}',
    [\App\Http\Controllers\EmployeeController::class, 'getEmployeeById'])->middleware('auth:sanctum');
Route::post('organization/{org_id}/employee',
    [\App\Http\Controllers\EmployeeController::class, 'сreateEmployee'])->middleware('auth:sanctum');
Route::put('organization/{org_id}/employee/{employee_id}',
    [\App\Http\Controllers\EmployeeController::class, 'editEmployee'])->middleware('auth:sanctum');
Route::patch('organization/{org_id}/employee/{employee_id}',
    [\App\Http\Controllers\EmployeeController::class, 'editEmployee'])->middleware('auth:sanctum');
Route::delete('organization/{org_id}/employee/{employee_id}',
    [\App\Http\Controllers\EmployeeController::class, 'deleteEmployee'])->middleware('auth:sanctum');

//Получение всех организаций
Route::get('organizations',
    [\App\Http\Controllers\OrganizationController::class, 'getAll'])->middleware('auth:sanctum')->name('organizations');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
