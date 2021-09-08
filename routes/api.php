<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiResidentController as Resident;
use App\Http\Controllers\ApiDocumentsController as Docs;
use App\Http\Controllers\ApiOfficialsController as Officials;
use App\Http\Controllers\ApiAdminController as Admin;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// All Users...
Route::middleware('admin_api')->group(function(){
    Route::get('/resident', [Resident::class, 'resident']);
    Route::get('/residents', [Resident::class, 'residents']);

    Route::get('/documents', [Docs::class, 'documents']);
    Route::get('/borrowed', [Docs::class, 'borrowed']);

    Route::post('/edit_account', [Admin::class, 'edit_account']);
    Route::get('/loggedin_admin', [Admin::class, 'loggedin_admin']);
});

// Super Admin and Admin...
Route::middleware('admin_api:2')->group(function(){
    Route::get('/officials', [Officials::class, 'officials']);
    Route::get('/assignatory', [Officials::class, 'assignatory']);
    
    Route::post('/modify_resident', [Resident::class, 'modify_resident']);
    
    Route::post('/modify_docs', [Docs::class, 'modify_docs']);
    Route::post('/modify_borrowed', [Docs::class, 'modify_borrowed']);
    
    Route::post('/modify_official', [Officials::class, 'modify_official']);
    Route::post('/set_assignatory', [Officials::class, 'set_assignatory']);
});

// Super Admin...
Route::middleware('admin_api:1')->group(function(){
    Route::get('/admins', [Admin::class, 'admins']);
    Route::post('/modify_admin', [Admin::class, 'modify_admin']);
});