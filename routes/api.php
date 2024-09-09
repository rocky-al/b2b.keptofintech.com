<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;  
use App\Http\Controllers\StaffController;  

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


Route::any('paymnetCancel', [StaffController::class, 'paymnetCancel']);
Route::any('paymnetFail', [StaffController::class, 'paymnetFail']);
Route::any('paymnetSuccess', [StaffController::class, 'paymnetSuccess']);
Route::any('paymnetCheckStatus', [StaffController::class, 'txnStatusEnquiry']);


Route::post('home2', [ApiController::class,'register'])->middleware('log.route');
Route::post('login', [ApiController::class,'login'])->middleware('log.route');
Route::post('social_login', [ApiController::class,'socialLogin'])->middleware('log.route');
Route::post('resendOTP', [ApiController::class,'resendOTP'])->middleware('log.route');
Route::post('updateSettings', [ApiController::class,'updateSettings'])->middleware('log.route');
Route::post('test_notificaiton', [ApiController::class,'test_notification'])->middleware('log.route');

Route::post('checkPromoCode', [ApiController::class,'checkPromoCode'])->middleware('log.route');
Route::group(['middleware' => 'local-api'], function(){
    Route::get('get_settings', [ApiController::class,'getSettings'])->middleware('log.route');
    Route::post('update_profile', [ApiController::class,'updateProfile'])->middleware('log.route');
    Route::get('logout', [ApiController::class,'logout'])->middleware('log.route');
    Route::get('delete_account', [ApiController::class,'deleteAccount'])->middleware('log.route');
    Route::post('read_notification', [ApiController::class,'readNotification'])->middleware('log.route');
    Route::post('upload_file', [ApiController::class,'uploadFile'])->middleware('log.route');
    Route::post('create_card_token', [ApiController::class,'createCardToken'])->middleware('log.route');
    Route::post('save_card', [ApiController::class,'saveCard'])->middleware('log.route');
    Route::get('get_all_save_card', [ApiController::class,'getAllSavedCard'])->middleware('log.route');
    Route::post('generate_payment_token', [ApiController::class,'generatePaymentToken'])->middleware('log.route');
    Route::post('delete_card', [ApiController::class,'deleteCard'])->middleware('log.route');
    Route::post('update_card', [ApiController::class,'updateCard'])->middleware('log.route');
    
    // 14-04-2023
    Route::post('verify_bank_details', [ApiController::class,'checkAccountDetails'])->middleware('log.route');
    Route::post('verify_bank_details_test', [ApiController::class,'checkAccountDetailsKp'])->middleware('log.route');
    Route::post('save_transaction', [ApiController::class,'saveTransaction'])->middleware('log.route'); 
    Route::post('get_transaction_history', [ApiController::class,'getTransactionHistory'])->middleware('log.route'); 
    Route::post('verify_card_for_credit_card_payment', [ApiController::class,'verifyCardForCreditCardPayment'])->middleware('log.route');
    Route::post('payment_analysis', [ApiController::class,'paymentAnalysis'])->middleware('log.route');
    Route::post('contact_support', [ApiController::class,'contactSupport'])->middleware('log.route');
    
    Route::post('create_vendor_payment', [ApiController::class,'createVendorPayment'])->middleware('log.route');  // this is not used   

    Route::post('notification_list', [ApiController::class,'notificationList'])->middleware('log.route');
    Route::post('add_product', [ApiController::class,'addProduct'])->middleware('log.route'); 
});




