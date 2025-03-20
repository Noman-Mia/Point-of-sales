<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[HomeController::class,'index'])->name('home');
Route::get('/test',[HomeController::class,'test']);

//User all routes
Route::post('/user-registration',[UserController::class,'UserRegistration'])
->name('user.registration');
Route::post('/user-login',[UserController::class,'UserLogin'])
->name('user.login');

Route::middleware(TokenVerificationMiddleware::class)->group(function(){
    Route::post('/DashboardPage',[UserController::class,'DashboardPage']);
});

