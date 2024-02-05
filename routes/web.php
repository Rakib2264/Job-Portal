<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\HomeController;
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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/account/registation',[AccountController::class,'registation'])->name('registation');
Route::post('/account/process_register',[AccountController::class,'processRegistation'])->name('processRegistation');
Route::get('/account/process_login',[AccountController::class,'login'])->name('processLogin');
