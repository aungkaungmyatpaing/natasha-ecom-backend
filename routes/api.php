<?php

use App\Http\Controllers\API\AdsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BrandController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\BannerController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\CurrencyRateController;
use App\Http\Controllers\API\CustomerController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//auth
Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class,'login']);
    Route::post('register', [AuthController::class,'register']);
    Route::get('logout', [AuthController::class,'logout'])->middleware('auth:api');
    Route::post('delete-account', [AuthController::class,'delete'])->middleware('auth:api');

    //banners
    Route::get('banners', [BannerController::class,'index']);

    //brands
    Route::get('brands', [BrandController::class,'index']);

    //category
    Route::get('categories', [CategoryController::class,'index']);

    //products
    Route::get('/products', [ProductController::class, 'listing']);
    Route::get('products/{id}', [ProductController::class,'detail']);
    Route::get('products/{id}/images',[ProductController::class,'productImages']);

    //ads
    Route::get('/ads',[AdsController::class,'index']);

    //currency_rate
    Route::get('/currencies',[CurrencyRateController::class,'index']);
});

Route::prefix('v1')->middleware(['auth:api','bannedCustomerCheck'])->group(function () {
    //customers
    Route::get('customer', [CustomerController::class,'index']);
    Route::put('customer', [CustomerController::class,'update']);
    Route::put('customer/update-password', [CustomerController::class,'updatePassword']);

    //payments
    Route::get('payments', [PaymentController::class,'index']);

    //order
    Route::post('orders', [OrderController::class,'create']);
    Route::get('orders/{id}', [OrderController::class,'detail']);
    Route::get('orders', [OrderController::class,'list']);

    //preorder
    Route::get('preorders', [OrderController::class,'preorderlist']);
    Route::post('preorders',[OrderController::class,'preorder']);

});
