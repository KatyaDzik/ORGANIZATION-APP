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

// Route::get('/', function () {
//     return view('organizations');
// });

Route::get('/', 'App\Http\Controllers\OrganizationController@getAll')->name('organizations');
Route::get('/org/{id}', 'App\Http\Controllers\OrganizationController@getOrgById')->name('org-data-by-id');
Route::post('/org/{id}/adduser', 'App\Http\Controllers\UserController@CreateUser');

Route::post('/user/submit', 'App\Http\Controllers\UserController@submit')->name('add-user-form');
Route::get('/user/{id}', 'App\Http\Controllers\UserController@getUserById')->name('user-data-by-id');

 Route::get('/loadxml', function () {
     return view('load-xml');
 })->name('loadxml');

Route::post('/loaddata', 'App\Http\Controllers\XMLController@loadData')->name('load-data-from-xml');


