<?php

use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//Получение всех организаций
Route::get('/', 'App\Http\Controllers\OrganizationController@getAll')->name('organizations');
//Удаление связи между пользователем и организацией
Route::delete('/org/{org_id}/delete/employee/{employee_id}', 'App\Http\Controllers\OrganizationController@deleteEmployeeFromOrg')->name('delete-employee-from-org');



//CRUD для организации
Route::get('/org/{id}', 'App\Http\Controllers\OrganizationController@getOrgById')->name('org-data-by-id');
Route::post('/create', 'App\Http\Controllers\OrganizationController@createOrg')->name('create-org');
Route::put('/org/edit/{id}', 'App\Http\Controllers\OrganizationController@editOrg')->name('edit-org-data');
Route::delete('/org/delete/{id}', 'App\Http\Controllers\OrganizationController@deleteOrgById')->name('delete-org-by-id');


//Route::get('/org/{id}/delete', 'App\Http\Controllers\OrganizationController@deleteOrgById')->name('delete-org-by-id');

//CRUD для сотрудника
Route::get('/employee/{id}', 'App\Http\Controllers\EmployeeController@getEmployeeById')->name('employee-data-by-id');
//Создание сотрудника, с привязкой к организации
Route::post('/org/{id}/create', 'App\Http\Controllers\EmployeeController@CreateEmployee')->name('create-employee');
Route::put('/employee/edit/{id}', 'App\Http\Controllers\EmployeeController@editEmployee')->name('update-employee');
Route::delete('/employee/delete/{id}', 'App\Http\Controllers\EmployeeController@deleteEmployee')->name('delete-employee');


//открыть страницу с файлом загрузки
Route::get('/loadxml', function () {
     return view('load-xml');
 })->name('loadxml');

//отправить файл загрузки
Route::post('/loaddata', 'App\Http\Controllers\XMLController@loadData')->name('load-data-from-xml');


