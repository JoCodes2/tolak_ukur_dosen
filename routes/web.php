<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CMS\AktivitasMengajarController;
use App\Http\Controllers\CMS\AktivitasPerkuliahanController;
use App\Http\Controllers\CMS\AktivitasPesertaController;
use App\Http\Controllers\CMS\DashboardController;
use App\Http\Controllers\CMS\KelasController;
use App\Http\Controllers\CMS\KontrakPerkuliahanController;
use App\Http\Controllers\CMS\KomponenController;
use App\Http\Controllers\CMS\MahasiswaController;
use App\Http\Controllers\CMS\MataKuliahController;
use App\Http\Controllers\CMS\NilaiMahasiswaController;
use App\Http\Controllers\CMS\PeriodeController;
use App\Http\Controllers\CMS\ProdiController;
use App\Http\Controllers\CMS\UserController;
use App\Models\ProdiModel;
use Illuminate\Support\Facades\Route;




Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::post('sicici/login', [LoginController::class, 'login']);

Route::middleware(['auth', 'web'])->group(function () {
    // route admin
    Route::get('/', [DashboardController::class, 'index']);

    // pages master data
    Route::get('/prodi', function () {
        return view('pages.prodi');
    });
    Route::get('/kelas', function () {
        return view('pages.kelas');
    });
    Route::get('/mahasiswa', function () {
        $prodi = ProdiModel::all();
        return view('pages.mahasiswa', compact('prodi'));
    });
    Route::get('/periode', function () {
        return view('pages.periode');
    });
    Route::get('/matakuliah', function () {
        return view('pages.matakuliah');
    });

    // pages  management pengguna dan dosen
    Route::get('/user', function () {
        $prodi = ProdiModel::all();
        return view('pages.user', compact('prodi'));
    });

    //  Route::get('/user', function () {
    //     return view('pages.user');
    // });


    // pages komponen
    Route::get('/komponen', function () {
        return view('pages.komponen');
    });

    // pages aktivitas perkuliahan
    Route::get('/aktivitas-perkuliahan', function () {
        return view('pages.aktivitas-perkuliahan');
    });

    Route::get('/aktivitas-perkuliahan', function () {
        return view('pages.aktivitas-perkuliahan');
    });
    Route::get('/aktivitas-perkuliahan/detail/{id}', function ($id) {
        return view('pages.aktivitas-detail', ['id_aktivitas' => $id]);
    })->name('aktivitas.detail');

    // kontrak perkuliahan dan penilaian
    Route::get('/kontrak-perkuliahan', function () {
        return view('pages.kontrak-perkuliahan');
    });
    Route::get('/kontrak-perkuliahan/detail/{id}', function ($id) {
        return view('pages.kontrak-perkuliahan-detail', ['id' => $id]);
    });

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
        Route::prefix('penugasan')->controller(AktivitasMengajarController::class)->group(function () {
            Route::get('/master-dropdown', 'getDropdownMaster');
            Route::get('/{id_aktivitas}', 'getMengajarByAktivitas');
            Route::post('/store', 'storePenugasan');
            Route::delete('/delete/{id}', 'deletePenugasan');
        });
    });
    // kontrak perkuliahan
    Route::prefix('kontrak-perkuliahan')->controller(KontrakPerkuliahanController::class)->group(function () {
        Route::get('/data', 'getAllData');
        Route::get('/komponen-tersedia/{idMengajarDetail}', 'getKomponen');
        Route::get('/bobot/{idMengajarDetail}', 'getBobot');
        Route::post('/sync/{idMengajarDetail}', 'syncKomponen');
        Route::post('/store', 'storeBobot');
    });
    Route::prefix('komponen')->controller(KomponenController::class)->group(function () {
        Route::get('/', 'getAllData');
        Route::post('/create', 'createData');
        Route::get('/get/{id}', 'getDataById');
        Route::post('/update/{id}', 'updateData');
        Route::delete('/delete/{id}', 'deleteData');
    });
    // Nilai Mahasiswa
    Route::prefix('penilaian-mahasiswa')->controller(NilaiMahasiswaController::class)->group(function () {
        Route::get('/daftar-mengajar', 'getDaftarMengajarDosen');
        Route::get('/detail-kelas/{idMengajarDetail}', 'getDetailPenilaianKelas');
        Route::post('/simpan', 'simpanNilaiMahasiswa');
        Route::post('/finalisasi/{idMengajarDetail}', 'finalisasiNilai');
    });
});
