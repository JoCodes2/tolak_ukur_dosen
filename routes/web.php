<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CMS\AktivitasMengajarController;
use App\Http\Controllers\CMS\AktivitasPerkuliahanController;
use App\Http\Controllers\CMS\AktivitasPesertaController;
use App\Http\Controllers\CMS\KelasController;
use App\Http\Controllers\CMS\MahasiswaController;
use App\Http\Controllers\CMS\MataKuliahController;
use App\Http\Controllers\CMS\PeriodeController;
use App\Http\Controllers\CMS\ProdiController;
use App\Http\Controllers\CMS\UserController;
use Illuminate\Support\Facades\Route;




Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('sicici/login', [LoginController::class, 'login']);

Route::middleware(['auth', 'web'])->group(function () {
    // route admin
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
    Route::get('/aktivitas-perkuliahan', function () {
        return view('pages.aktivitas-perkuliahan');
    });

    // pages aktivitas perkuliahan
    Route::get('/aktivitas-perkuliahan', function () {
        return view('pages.aktivitas-perkuliahan');
    });

    Route::get('/aktivitas-perkuliahan/detail/{id}', function ($id) {
        return view('pages.aktivitas-detail', ['id_aktivitas' => $id]);
    })->name('aktivitas.detail');
    Route::post('sicici/logout', [LoginController::class, 'logout']);
});


// route api
Route::prefix('sicici')->group(function () {
    // master data
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

    // managemenet user
    Route::prefix('user')->controller(UserController::class)->group(function () {
        Route::get('/', 'getAllData');
        Route::post('/create', 'createData');
        Route::get('/get/{id}', 'getDataById');
        Route::post('/update/{id}', 'updateData');
        Route::delete('/delete/{id}', 'deleteData');
    });

    // aktivitas perkuliahan
    Route::prefix('aktivitas-perkuliahan')->controller(AktivitasPerkuliahanController::class)->group(function () {
        Route::controller(AktivitasPerkuliahanController::class)->group(function () {
            Route::get('/', 'getAllData');
            Route::post('/create', 'createData');
            Route::get('/get/{id}', 'getDataById');
            Route::post('/update/{id}', 'updateData');
            Route::delete('/delete/{id}', 'deleteData');
        });

        // Detail Peserta (Mahasiswa)
        Route::prefix('peserta')->controller(AktivitasPesertaController::class)->group(function () {
            Route::get('/{id_aktivitas}', 'getPeserta');
            Route::get('/kolektif/tersedia', 'getMahasiswaTersedia');
            Route::post('/kolektif/store', 'storeKolektif');
            Route::delete('/delete/{id}', 'deleteData');
        });

        // Detail Mengajar (Dosen & MK)
        Route::prefix('pengajar')->controller(AktivitasMengajarController::class)->group(function () {
            Route::get('/{id_aktivitas}', 'getPengajar');
            Route::post('/store', 'storePenugasan');
            Route::delete('/delete/{id}', 'deleteData');
        });
    });
});
