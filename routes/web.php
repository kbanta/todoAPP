<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::post('home/register', [App\Http\Controllers\HomeController::class, 'register_user'])->name('registerAccount');
Route::get('/home/edit/{id}', [App\Http\Controllers\HomeController::class, 'edit_user']);
Route::patch('/home/update/{id}', [App\Http\Controllers\HomeController::class, 'update_user']);
Route::patch('home/deleteUser/{id}', [App\Http\Controllers\HomeController::class, 'delete_user'])->name('delete-user');

