<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;

Route::get('/',[HomeController::class,'index'])->name('appHome');
Route::get('/login',[AuthController::class,'showLoginForm'])->name('loginForm');
Route::post('/login',[AuthController::class,'processLoginReport'])->name('processLoginReport');
Route::get('/product/{productId}',[HomeController::class,'productDetail'])->name('productDetail');
Route::post('payment', [HomeController::class,'processPayment'])->name('processPayment');
