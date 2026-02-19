<?php

use App\Http\Controllers\CMS\ProdiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
});

Route::get('/user', function () {
    return view('admin.user');
});

// pages
Route::get('/prodi', function () {
    return view('pages.prodi');
});
// route api
Route::prefix('sicici')->group(function () {
    Route::prefix('prodi')->controller(ProdiController::class)->group(function () {
        Route::get('/', 'getAllData');
        Route::post('/create', 'createData');
        Route::get('/get/{id}', 'getDataById');
        Route::post('/update/{id}', 'updateData');
        Route::delete('/delete/{id}', 'deleteData');
    });
});
