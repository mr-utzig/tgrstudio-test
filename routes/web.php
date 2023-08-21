<?php

use App\Http\Controllers\ProductsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(ProductsController::class)
->group(function () {
    Route::get("/", "list")->name('products.list');
    Route::get("/product/{id}", "show");
    Route::post("/product", "insert");
    Route::patch("/product/{id}", "update");
    Route::delete("/product/{id}", "delete");
    Route::get("/products/filter", "filter")->name('products.filter');
});