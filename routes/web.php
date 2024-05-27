<?php

use App\Http\Controllers\AdsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\AuthContoller;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\PaymentController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CustomerController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\CurrencyRateController;
use App\Http\Controllers\Other\ApplicationController;

Route::get('/',function(){
    if(Auth::check()){
        return redirect()->route('dashboard');
    }
    return view('welcome');
})->name('home');

Route::get('/privacy-policy',function(){
    return view('privacy-policy');
})->name('privacyPolicy');

//Auth
Route::get('/narita-admin-login',[AuthContoller::class,'login'])->name('login');
Route::post('/narita-admin-login',[AuthContoller::class,'postLogin'])->name('postLogin');
Route::get('/edit-password',[AuthContoller::class,'editPassword'])->name('editPassword')->middleware('auth');
Route::post('/edit-password',[AuthContoller::class,'updatePassword'])->name('updatePassword')->middleware('auth');
Route::get('/logout',[AuthContoller::class,'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function(){
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //profile
    Route::get('/edit-profile',[AuthContoller::class,'editProfile'])->name('profile.edit');
    Route::post('/edit-profile',[AuthContoller::class,'updateProfile'])->name('profile.update');

    //Products
    Route::get('/products', [ProductController::class, 'listing'])->name('product');
    Route::get('/products/datatable/ssd', [ProductController::class, 'serverSide']);

    Route::get('/products/create', [ProductController::class, 'create'])->name('product.create');
    Route::post('/products', [ProductController::class, 'store'])->name('product.store');
    Route::get('/products/{id}', [ProductController::class, 'detail'])->name('product.detail');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('product.edit');
    Route::put('/products/{id}/update', [ProductController::class, 'update'])->name('product.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('product.destroy');

    Route::get('product-images/{id}', [ProductController::class, 'images']); // get images from edit

    // Categories
    Route::get('/categories', [CategoryController::class, 'index'])->name('category');
    Route::get('/categories/datatable/ssd', [CategoryController::class, 'serverSide']);

    Route::get('/categories/create', [CategoryController::class, 'create'])->name('category.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('category.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::put('/categories/{id}/update', [CategoryController::class, 'update'])->name('category.update');
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('category.destroy');

    // Brands
    Route::get('/brands', [BrandController::class, 'index'])->name('brand');
    Route::get('/brands/datatable/ssd', [BrandController::class, 'serverSide']);

    Route::get('/brands/create', [BrandController::class, 'create'])->name('brand.create');
    Route::post('/brands', [BrandController::class, 'store'])->name('brand.store');
    Route::get('/brands/{id}/edit', [BrandController::class, 'edit'])->name('brand.edit');
    Route::put('/brands/{id}/update', [BrandController::class, 'update'])->name('brand.update');
    Route::delete('/brands/{id}', [BrandController::class, 'destroy'])->name('brand.destroy');

    //banners
    Route::get('/banners',[BannerController::class,'index'])->name('banner');
    Route::get('/banners/datatable/ssd', [BannerController::class, 'serverSide']);

    Route::get('/banners/create',[BannerController::class,'create'])->name('banner.create');
    Route::post('/banners/create',[BannerController::class,'store'])->name('banner.store');
    Route::get('/banners/edit/{id}',[BannerController::class,'edit'])->name('banner.edit');
    Route::post('/banners/edit/{id}',[BannerController::class,'update'])->name('banner.update');
    Route::delete('/banners/{id}',[BannerController::class,'destroy'])->name('banner.destroy');

    //payments
    Route::get('/payments',[PaymentController::class,'index'])->name('payment');
    Route::get('/payments/datatable/ssd', [PaymentController::class, 'serverSide']);

    Route::get('/payments/create',[PaymentController::class,'create'])->name('payment.create');
    Route::post('/payments/create',[PaymentController::class,'store'])->name('payment.store');
    Route::get('/payments/edit/{id}',[PaymentController::class,'edit'])->name('payment.edit');
    Route::post('/payments/edit/{id}',[PaymentController::class,'update'])->name('payment.update');
    Route::delete('/payments/{id}',[PaymentController::class,'destroy'])->name('payment.destroy');

    //customers
    Route::get('/customers',[CustomerController::class,'index'])->name('customer');
    Route::get('/customers/{id}',[CustomerController::class,'detail'])->name('customer.detail');

    Route::get('/customers/edit/{id}',[CustomerController::class,'edit'])->name('customer.edit');
    Route::put('/customers/edit/{id}',[CustomerController::class,'update'])->name('customer.update');
    Route::put('/customers/update-password/{id}',[CustomerController::class,'updatePassword'])->name('customer.updatePassword');
    Route::post('/customers/ban/{id}',[CustomerController::class,'banCustomer'])->name('customer.ban');

    Route::get('/customers/datatable/ssd', [CustomerController::class, 'serverSide']);

    //orders
    Route::get('/orders',[OrderController::class,'index'])->name('order');
    Route::get('/orders/pending',[OrderController::class,'pendingOrder'])->name('order.pending');
    Route::get('/orders/{id}',[OrderController::class,'detail'])->name('order.detail');
    Route::post('/orders/{id}',[OrderController::class,'updateStatus'])->name('order.updateStatus');

    Route::get('/all-orders/datatable/ssd', [OrderController::class, 'getAllOrder']);
    Route::get('/pending-orders/datatable/ssd', [OrderController::class, 'getPendingOrder']);

    //preorders
    Route::get('/preorders',[OrderController::class,'preOrder'])->name('preorder');
    Route::get('/preorders/datatable/ssd', [OrderController::class, 'getPreOrder']);

    //ads
    Route::get('/ads',[AdsController::class,'index'])->name('ads');

    Route::get('/ads/create',[AdsController::class,'create'])->name('ads.create');
    Route::post('/ads/create',[AdsController::class,'store'])->name('ads.store');
    Route::get('/ads/edit/{id}',[AdsController::class,'edit'])->name('ads.edit');
    Route::post('/ads/edit/{id}',[AdsController::class,'update'])->name('ads.update');
    Route::delete('/ads/{id}',[AdsController::class,'destroy'])->name('ads.destroy');

    Route::get('/ads/datatable/ssd', [AdsController::class, 'serverSide']);

    //currency
    Route::get('/currencies',[CurrencyRateController::class,'index'])->name('currency');

    Route::get('/currencies/create',[CurrencyRateController::class,'create'])->name('currency.create');
    Route::post('/currencies/create',[CurrencyRateController::class,'store'])->name('currency.store');
    Route::get('/currencies/edit/{id}',[CurrencyRateController::class,'edit'])->name('currency.edit');
    Route::post('/currencies/edit/{id}',[CurrencyRateController::class,'update'])->name('currency.update');
    Route::delete('/currencies/{id}',[CurrencyRateController::class,'destroy'])->name('currency.destroy');

    Route::get('/currencies/datatable/ssd', [CurrencyRateController::class, 'serverSide']);


});

Route::get('image/{filename}', [ApplicationController::class, 'image'])->where('filename', '.*');