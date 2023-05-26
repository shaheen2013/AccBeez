<?php

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

Route::get('/', function () {
    return view('master');
});

// Route::get('/{any}', function () {
//     // return view('layouts.app');
//     return view('master');
// })->where('any','.*');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/api/bills', [App\Http\Controllers\BillController::class, 'index'])->name('bills.list');
Route::post('/api/bills', [App\Http\Controllers\BillController::class, 'store'])->name('bills.store');
Route::get('/api/bills/edit/{id}', [App\Http\Controllers\BillController::class, 'edit'])->name('bills.edit');
Route::post('/api/bills/{id}', [App\Http\Controllers\BillController::class, 'update'])->name('bills.update');
Route::delete('/api/bills/{id}', [App\Http\Controllers\BillController::class, 'delete'])->name('bills.delete');


Route::get('/api/boms', [App\Http\Controllers\BomController::class, 'index'])->name('boms.list');
Route::post('/api/boms', [App\Http\Controllers\BomController::class, 'store'])->name('boms.store');
Route::get('/api/boms/edit/{id}', [App\Http\Controllers\BomController::class, 'edit'])->name('boms.edit');
Route::post('/api/boms/{id}', [App\Http\Controllers\BomController::class, 'update'])->name('boms.update');


Route::get('/api/registers', [App\Http\Controllers\RegisterController::class, 'index'])->name('registers');
