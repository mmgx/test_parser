<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});



Route::group([
    'prefix' => 'category',
    'as' => 'category.'], function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
});
