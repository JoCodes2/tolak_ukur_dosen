<?php

use App\Http\Controllers\CMS\KelasController;
use App\Http\Controllers\CMS\MahasiswaController;
use App\Http\Controllers\CMS\MataKuliahController;
use App\Http\Controllers\CMS\PeriodeController;
use App\Http\Controllers\CMS\ProdiController;
use App\Http\Controllers\CMS\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
});

// pages master data
Route::get('/prodi', function () {
    return view('pages.prodi');
});
Route::get('/kelas', function () {
    return view('pages.kelas');
});
Route::get('/mahasiswa', function () {
    return view('pages.mahasiswa');
});
Route::get('/periode', function () {
    return view('pages.periode');
});
Route::get('/matakuliah', function () {
    return view('pages.matakuliah');
});

// pages  management pengguna dan dosen
Route::get('/user', function () {
    return view('pages.user');
});


// pages aktivitas perkuliahan


// pages monitoring penilaian


// route api
Route::prefix('sicici')->group(function () {
    Route::prefix('prodi')->controller(ProdiController::class)->group(function () {
        Route::get('/', 'getAllData');
        Route::post('/create', 'createData');
        Route::get('/get/{id}', 'getDataById');
        Route::post('/update/{id}', 'updateData');
        Route::delete('/delete/{id}', 'deleteData');
    });

    Route::prefix('kelas')->controller(KelasController::class)->group(function () {
        Route::get('/', 'getAllData');
        Route::post('/create', 'createData');
        Route::get('/get/{id}', 'getDataById');
        Route::post('/update/{id}', 'updateData');
        Route::delete('/delete/{id}', 'deleteData');
    });

    Route::prefix('mahasiswa')->controller(MahasiswaController::class)->group(function () {
        Route::get('/', 'getAllData');
        Route::post('/create', 'createData');
        Route::get('/get/{id}', 'getDataById');
        Route::post('/update/{id}', 'updateData');
        Route::delete('/delete/{id}', 'deleteData');
    });

    Route::prefix('periode')->controller(PeriodeController::class)->group(function () {
        Route::get('/', 'getAllData');
        Route::post('/create', 'createData');
        Route::get('/get/{id}', 'getDataById');
        Route::post('/update/{id}', 'updateData');
        Route::delete('/delete/{id}', 'deleteData');
    });

    Route::prefix('matakuliah')->controller(MataKuliahController::class)->group(function () {
        Route::get('/', 'getAllData');
        Route::post('/create', 'createData');
        Route::get('/get/{id}', 'getDataById');
        Route::post('/update/{id}', 'updateData');
        Route::delete('/delete/{id}', 'deleteData');
    });

    Route::prefix('user')->controller(UserController::class)->group(function () {
        Route::get('/', 'getAllData');
        Route::post('/create', 'createData');
        Route::get('/get/{id}', 'getDataById');
        Route::post('/update/{id}', 'updateData');
        Route::delete('/delete/{id}', 'deleteData');
    });
});
