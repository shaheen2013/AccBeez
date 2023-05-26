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

// Route::get('/{any}', function () {
//     // return view('layouts.app');
//     return view('master');
// })->where('any','.*');

Route::get('/{any}', function () {
    // dd(Auth::check());
    if (Auth::check()) {
        return view('master');
    } else {
        return redirect('/login');
    }
})->where('any', '^(?!login).*$');


// Route::get('/{any}', function () {
//     if (request()->path() === 'login') {
//         return view('master');
//     } else {
//         return redirect('/login');
//     }
// })->where('any', '^(?!login).*$');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

