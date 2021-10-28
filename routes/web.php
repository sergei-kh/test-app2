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
Route::get('/stock-manager', [PageController::class, 'stockManager'])->name('stock_manager');

Route::get('/storages', [PageController::class, 'storage'])->name('storage');

Route::get('/storages/products/{id}', [PageController::class, 'storageProducts'])
    ->name('storage_product')
    ->where('id', '[0-9]+');

Route::get('/storages/story/{id}', [PageController::class, 'storageStories'])
    ->name('storage_story')
    ->where('id', '[0-9]+');

Route::get('/products', [PageController::class, 'products'])->name('products');

Route::get('/products/story/{id}', [PageController::class, 'productsStory'])
    ->name('products_story')
    ->where('id', '[0-9]+');

Route::get('/storage/products', [StorageController::class, 'indexProducts']);
Route::post('/storage/transfer', [StorageController::class, 'transfer']);
Route::resource('storage', StorageController::class);
