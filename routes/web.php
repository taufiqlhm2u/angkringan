<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function() {
        Route::resource('category', CategoryController::class);
        Route::resource('product', ProductController::class);
        Route::resource('report', ReportController::class);
    });
});

require __DIR__.'/settings.php';
