<?php

use App\Http\Controllers\ExportController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
// Route::middleware('auth:api')->get('/session', function () {
//     return session()->all();
// });
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('role:Super-Admin')->group(function(){
    Route::get('/bills', [App\Http\Controllers\BillController::class, 'index'])->name('bills.list');
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.list');
    
});

Route::middleware('role:Admin')->group(function(){
    Route::post('/bills', [App\Http\Controllers\BillController::class, 'store'])->name('bills.store');

});





Route::get('/bills/edit/{id}', [App\Http\Controllers\BillController::class, 'edit'])->name('bills.edit');
Route::post('/bills/bulkdelete', [App\Http\Controllers\BillController::class, 'bulkdelete'])->name('bills.bulkdelete');
Route::post('/bills/{id}', [App\Http\Controllers\BillController::class, 'update'])->name('bills.update');
Route::delete('/bills/{id}', [App\Http\Controllers\BillController::class, 'delete'])->name('bills.delete');


Route::get('/boms', [App\Http\Controllers\BomController::class, 'index'])->name('boms.list');
Route::post('/boms', [App\Http\Controllers\BomController::class, 'store'])->name('boms.store');
Route::get('/boms/edit/{id}', [App\Http\Controllers\BomController::class, 'edit'])->name('boms.edit');
Route::post('/boms/bulkdelete', [App\Http\Controllers\BomController::class, 'bulkdelete'])->name('boms.bulkdelete');
Route::post('/boms/{id}', [App\Http\Controllers\BomController::class, 'update'])->name('boms.update');
Route::delete('/boms/{id}', [App\Http\Controllers\BomController::class, 'delete'])->name('boms.delete');


Route::post('/users', [App\Http\Controllers\UserController::class, 'store'])->name('users.store');
Route::get('/users/edit/{id}', [App\Http\Controllers\UserController::class, 'edit'])->name('users.edit');
Route::post('/users/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [App\Http\Controllers\UserController::class, 'delete'])->name('users.delete');

// Route::apiResource('sales', \App\Http\Controllers\SaleController::class);
Route::get('/sales', [App\Http\Controllers\SaleController::class, 'index'])->name('sales.list');
Route::post('/sales', [App\Http\Controllers\SaleController::class, 'store'])->name('sales.store');
Route::get('/sales/edit/{id}', [App\Http\Controllers\SaleController::class, 'edit'])->name('sales.edit');
Route::post('/sales/{id}', [App\Http\Controllers\SaleController::class, 'update'])->name('sales.update');
Route::delete('/sales/{id}', [App\Http\Controllers\SaleController::class, 'delete'])->name('sales.delete');

Route::get('/products', [App\Http\Controllers\ProductController::class, 'index'])->name('products.list');


Route::get('/registers', [App\Http\Controllers\RegisterController::class, 'index'])->name('registers');
Route::get('/registers/view/{id}', [App\Http\Controllers\RegisterController::class, 'view'])->name('registers.view');

// export routes 

Route::get('/bill/{billId}/export/{fileName}',[ExportController::class,'exportBillXls']);
Route::get('/bill/blade/{billId}/export/{fileName}',[ExportController::class,'exportBillBladeXls']);
