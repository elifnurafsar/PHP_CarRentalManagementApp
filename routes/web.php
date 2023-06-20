<?php

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

Route::get('/', 'HomeController@index');

Auth::routes(['register' => false, 'reset' => false]);

Route::get('/home', 'HomeController@index')->name('home');

Route::middleware(['auth'])->group(function(){
    
    Route::get('/cash/getCarsByCategory/{category_id}', 'Cash\CashController@GetCarsByCategory');
    Route::post('/cash/AddToCard', 'Cash\CashController@AddToCard');
    Route::post('/cash/deleteSaleDetail', 'Cash\CashController@DeleteSaleDetail');
    Route::post('/cash/increaseQuantity', 'Cash\CashController@IncreaseQuantity');
    Route::post('/cash/decreaseQuantity', 'Cash\CashController@DecreaseQuantity');
    Route::get('/cash', 'Cash\CashController@index');
    Route::get('/cash/getCustomers', 'Cash\CashController@GetCustomers');
    Route::get('/cash/getSaleDetailsByCustomer/{customer_id}', 'Cash\CashController@GetSaleDetailsByCustomer');
    Route::post('/cash/confirmOrderStatus', 'Cash\CashController@ConfirmOrderStatus');
    Route::post('/cash/savePayment', 'Cash\CashController@SavePayment');
    Route::get('/cash/showReceipt/{saleID}', 'Cash\CashController@ShowReceipt');

});

Route::middleware(['auth', 'VerifyAdmin'])->group(function(){
    Route::get('/management', function(){
        return view('management.index');
    });
    
    Route::resource('management/category', 'Management\CategoryController');
    Route::resource('management/car', 'Management\CarController');
    Route::resource('management/customer', 'Management\CustomerController');
    Route::resource('management/user', 'Management\UserController');

    Route::get('/report', 'Report\ReportController@index');
    Route::get('/report/show', 'Report\ReportController@Show');

    // Export to excel
    Route::get('/report/show/export', 'Report\ReportController@Export');

});
