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

Route::get('/', 'App\Http\Controllers\OrganizationController@getAll');


Route::get('/adduser', function () {
    return view('/adduser');
});

Route::post('/user/submit', 'App\Http\Controllers\UserController@submit')->name('add-user-form');




