<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('products/datatable', [ProductController::class, 'serversideTable']);
Route::resource('products', ProductController::class)->except(['create', 'edit']);
