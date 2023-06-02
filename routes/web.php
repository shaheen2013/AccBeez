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
    if (Auth::check()) {
        return view('master');
    } else {
        return redirect('/login');
    }
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/confirm_registration', [App\Http\Controllers\UserController::class, 'confirm_registration'])->name('confirm_registration');
Route::post('/update_registration', [App\Http\Controllers\UserController::class, 'update_registration'])->name('update_registration');

Route::get('/logged_in_user', [App\Http\Controllers\UserController::class, 'logged_in_user'])->name('logged_in_user');
Route::get('/bills/download-pdf/{id}', [App\Http\Controllers\BillController::class, 'downloadPdf'])->name('bills.downloadPdf');
Route::get('/boms/download-pdf/{id}', [App\Http\Controllers\BomController::class, 'downloadPdf'])->name('boms.downloadPdf');


Route::get('/{any}', function () {
    // dd(Auth::check());
    if (Auth::check()) {
        return view('master');
    } else {
        return redirect('/login');
    }
})->where('any', '^(?!login).*$');
