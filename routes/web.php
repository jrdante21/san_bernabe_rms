<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController as Home;
use App\Http\Controllers\LoginController as Login;
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
Route::redirect('/', '/login');
Route::get('/login', [Login::class, 'home'])->name('login');
Route::get('/logout', [Login::class, 'logout']);
Route::post('/loginAuth', [Login::class, 'login']);

Route::get('/admin/{any?}', [Home::class, 'home'])->where('any', '.*')->name('admin');
Route::get('/print/report', [Home::class, 'print_report']);
Route::get('/print/document', [Home::class, 'print_document']);
Route::get('/print/resident', [Home::class, 'print_resident']);
Route::get('/print/residents', [Home::class, 'print_residents']);