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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/api/bills', [App\Http\Controllers\BillController::class, 'index'])->name('bills.list');
Route::post('/api/bills', [App\Http\Controllers\BillController::class, 'store'])->name('bills.store');
Route::get('/api/bills/edit/{id}', [App\Http\Controllers\BillController::class, 'edit'])->name('bills.edit');
Route::post('/api/bills/{id}', [App\Http\Controllers\BillController::class, 'update'])->name('bills.update');


Route::get('/api/registers', [App\Http\Controllers\RegisterController::class, 'index'])->name('registers');
