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



Route::group(['prefix' => 'account'], function () {

    // User Route
    Route::group(['middleware' => 'guest'], function () {
        Route::get('/registation', [AccountController::class, 'registation'])->name('registation');
        Route::post('/process_register', [AccountController::class, 'processRegistation'])->name('processRegistation');
        Route::get('/process_login', [AccountController::class, 'login'])->name('processLogin');
        Route::post('/authenticate', [AccountController::class, 'authenticate'])->name('authenticate');
    });

    // Auth Route
    Route::group(['middleware' => 'auth'], function () {
        Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
        Route::put('/profile/update', [AccountController::class, 'updateProfile'])->name('updateProfile');
        Route::get('/logout', [AccountController::class, 'logout'])->name('logout');
        Route::post('/update/pic', [AccountController::class, 'updateProfilePic'])->name('updateProfilePic');
    });

});


