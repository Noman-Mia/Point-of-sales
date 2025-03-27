<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\TokenVerificationMiddleware;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[HomeController::class,'index'])->name('home');


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
    Route::post('/update-product',[ProductController::class,'ProductUpdate'])
    ->name('ProductUpdate');
    Route::get('/delete-product/{id}', [ProductController::class, 'ProductDelete']);

    //customer all routes
    Route::post('/create-customer',[CustomerController::class,'createCustomer'])
    ->name('createCustomer');
    Route::get('/list-customer',[CustomerController::class,'CustomerList'])
    ->name('CustomerList');
    Route::get('/customer-by-id',[CustomerController::class,'CustomerById'])
    ->name('CustomerById');
    Route::post('/update-customer',[CustomerController::class,'CustomerUpdate'])
    ->name('CustomerUpdate');
    Route::get('/delete-customer/{id}', [CustomerController::class,'CustomerDelete'])->name('CustomerDelete');

    //invoice all routes
    Route::post('/invoice-create',[InvoiceController::class,'InvoiceCreate'])->name('InvoiceCreate');
    Route::get('/invoice-list',[InvoiceController::class,'InvoiceList'])->name('InvoiceList');
    Route::post('/invoice-details',[InvoiceController::class,'InvoiceDetails'])->name('InvoiceDetails');
    Route::get('/invoice-delete/{id}', [InvoiceController::class,'InvoiceDelete'])->name('InvoiceDelete');

    //dashboard summary
    Route::get('/dashboard-summary',[DashboardController::class,'DashboardSummary'])->name('DashboardSummary');

    //reset password page
    Route::get('/reset-password',[UserController::class,'ResetPasswordPage']);

});

//pages all routes
Route::get('/login',[UserController::class,'LoginPage'])
->name('login.page');
Route::get('/registration',[UserController::class,'RegistrationPage'])
->name('registration.page');
Route::get('/send-otp',[UserController::class,'SendOTPPage'])
->name('sendotp.page');
Route::get('/verify-otp',[UserController::class,'VerifyOTPPage'])
->name('VerifyOTPPage');

