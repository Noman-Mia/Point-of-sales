<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
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
Route::post('/send-otp',[UserController::class,'SendOTPCode'])
->name('SendOTPCode');
Route::post('/verify-otp',[UserController::class,'VerifyOTP'])
->name('VerifyOTP');


Route::middleware(TokenVerificationMiddleware::class)->group(function(){
    //reset password
    Route::post('/reset-password',[UserController::class,'ResetPassword']);
    Route::get('/DashboardPage',[UserController::class,'DashboardPage']);
    Route ::get('/User-logout',[UserController::class,'UserLogout']);
    //category all routes
    Route::post('/create-category',[CategoryController::class,'createCategory'])
    ->name('category.create');
    Route::post('/create-category',[CategoryController::class,'createCategory'])
    ->name('category.create');

    Route::get('/list-category',[CategoryController::class,'categoryList'])
    ->name('category.list');

    Route::post('/category-by-id',[CategoryController::class,'categoryById']);
    Route::post('/update-category',[CategoryController::class,'CategoryUpdate'])
    ->name('category.update');
    Route::get('/delete-category',[CategoryController::class,'CategoryDelete'])
    ->name('category.delete');

    //product all routes
    Route::post('/create-product',[ProductController::class,'createProduct'])
    ->name('createProduct');
    Route::get('/list-product',[ProductController::class,'ProductList'])
    ->name('ProductList');
    Route::get('/product-by-id',[ProductController::class,'ProductById'])
    ->name('ProductById');
    Route::get('/update-product',[ProductController::class,'ProductUpdate'])
    ->name('ProductUpdate');

});

