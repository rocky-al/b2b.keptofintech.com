<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\StaffController;



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

Route::get('callartisan', function () {
    Artisan::call('passport:install');
});


Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('password/forget', [LoginController::class, 'showForgotForm'])->name('password.forget');
//Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::post('password/email', [ForgotPasswordController::class, 'submitForgetPasswordForm'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');


Route::get('/privacy-policy', function () {
    return view('privacy');
});

Route::get('/AboutUs', function () {
    return view('AboutUs');
});

Route::get('/terms-and-conditions', function () {
    return view('Terms');
});

Route::get('/cancellation-policy', function () {
    return view('Cancellation');
});

Route::get('/services', function () {
    return view('Services');
});

Route::get('/ContactUs', function () {
    return view('ContactUs');
});



Route::group(['middleware' => 'auth'], function () {

    // logout route
Route::get('/logout', [LoginController::class, 'logout']);
Route::get('/clear-cache', [HomeController::class, 'clearCache']);
// Route::get('/index2', [HomeController::class, 'index']);

Route::get('log_save', [StaffController::class, 'log_save'])->name('log_save');

Route::get('home', [StaffController::class, 'index'])->name('users');
Route::get('getTxnList', [StaffController::class, 'getTxnList'])->name('getTxnList');
Route::get('transactions', [StaffController::class, 'transactions']);
Route::get('refund', [StaffController::class, 'refund']);
Route::get('getRefundList', [StaffController::class, 'getRefundList'])->name('getRefundList');

    // Route::get('users/list', [StaffController::class, 'list']);
Route::get('users/create', [StaffController::class, 'create']);
Route::get('users/edit/{id}', [StaffController::class, 'get_by_id']);
Route::get('users/view/{id}', [StaffController::class, 'user_view']);
Route::post('users/store', [StaffController::class, 'store']);
Route::post('users/update_status', [StaffController::class, 'update_status'])->name('update.status');

Route::post('users/refunduser', [StaffController::class, 'refunduser'])->name('refunduser');
Route::post('users/update_refund_status', [StaffController::class, 'update_refund_status'])->name('update.refund_status');
Route::post('users/passwordUpdate', [StaffController::class, 'passwordUpdate'])->name('passwordUpdate');
Route::post('users/delete', [StaffController::class, 'delete'])->name('delete.users');
Route::get('users/export', [StaffController::class, 'export'])->name('users.export');
Route::get('blocked_users', [StaffController::class, 'blocked_users']);
Route::get('users/blocked', [StaffController::class, 'blocked_users']);


Route::get('dashboardStates', [StaffController::class, 'dashboardStates'])->name('dashboardStates');


Route::get('paymnet', [StaffController::class, 'paymnetForm'])->name('paymnetForm');
Route::get('create-order', [StaffController::class, 'paymnetFormRazor'])->name('paymnetFormrazorpay');
Route::post('payment-callback', [StaffController::class, 'paymentCallback'])->name('payment.callback');




    //profile route
Route::get('employee/profile/{id}', [EmployeeController::class, 'edit']);
Route::post('employee/profile/update', [EmployeeController::class, 'update']);
Route::post('employee/profile/profile_image_update', [EmployeeController::class, 'profile_image_update']);
Route::get('change/password/{id}', [EmployeeController::class, 'change_password']);
Route::post('change/password/update', [EmployeeController::class, 'update']);

});

Route::get('/login-1', function () {
    return view('pages.login');
});
