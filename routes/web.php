<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Super\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    return redirect()->route('dashboard');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function() {
        Route::resource('category', CategoryController::class);
        Route::resource('product', ProductController::class);
        Route::resource('report', ReportController::class);
    });
    
    Route::middleware('super')->prefix('super')->name('super.')->group(function() {
        Route::resource('users', UserController::class);
    });
    
});

require __DIR__.'/settings.php';
