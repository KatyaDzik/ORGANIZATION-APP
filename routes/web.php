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
Route::delete('/org/{org_id}/delete/user/{user_id}', 'App\Http\Controllers\OrganizationController@deleteUserFromOrg')->name('delete-user-from-org');



//CRUD для организации
Route::get('/org/{id}', 'App\Http\Controllers\OrganizationController@getOrgById')->name('org-data-by-id');
Route::post('/create', 'App\Http\Controllers\OrganizationController@createOrg')->name('create-org');
Route::put('/org/{id}/edit', 'App\Http\Controllers\OrganizationController@editOrg')->name('edit-org-data');
Route::get('/org/{id}/delete', 'App\Http\Controllers\OrganizationController@deleteOrgById')->name('delete-org-by-id');

//CRUD для пользователя
Route::get('/user/{id}', 'App\Http\Controllers\UserController@getUserById')->name('user-data-by-id');
//Создание пользователя, с привязкой к организации
Route::post('/org/{id}/adduser', 'App\Http\Controllers\UserController@CreateUser');
Route::put('/user/edit/{id}', 'App\Http\Controllers\UserController@editUser')->name('update-user');
Route::delete('/user/delete/{id}', 'App\Http\Controllers\UserController@deleteUser')->name('delete-user');


//открыть страницу с файлом загрузки
Route::get('/loadxml', function () {
     return view('load-xml');
 })->name('loadxml');

//отправить файл загрузки
Route::post('/loaddata', 'App\Http\Controllers\XMLController@loadData')->name('load-data-from-xml');


