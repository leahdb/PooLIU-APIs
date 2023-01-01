<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MailController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//authentication routes
Route::post('register', [AuthController::class, 'Register']);
Route::post('verify', [AuthController::class, 'Verify']);
Route::post('login', [AuthController::class, 'Login']);
Route::post('logout', [AuthController::class, 'Logout']);
Route::post('forgotpassword',[AuthController::class, 'ForgotPassword']);
Route::post('resetpassword',[AuthController::class, 'ResetPassword']);
Route::post('edit',[AuthController::class, 'Edit']);