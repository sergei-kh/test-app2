<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\StorageController;

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

Route::get('/', [PageController::class, 'index'])->name('main');
Route::get('/stock-manager', [PageController::class, 'stockManager'])->name('stock-manager');
Route::get('/storages', [PageController::class, 'storage'])->name('storage');
Route::get('/products', [PageController::class, 'products'])->name('products');

Route::get('/storage/products', [StorageController::class, 'indexProducts']);
Route::post('/storage/transfer', [StorageController::class, 'transfer']);
Route::resource('storage', StorageController::class);
