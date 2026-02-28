<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Models\AktivitasPerkuliahanModel;
use App\Models\MahasiswaModel;
use App\Models\MataKuliahModel;
use App\Models\ProdiModel;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'total_mahasiswa' => MahasiswaModel::count(),
            'total_dosen'     => User::where('role', 'dosen')->count(), 
            'total_prodi'     => ProdiModel::count(),
            'total_matakuliah' => MataKuliahModel::count(),
            'prodi_distribution' => ProdiModel::all()->map(function($prodi) {
                return [
                    'nama_prodi' => $prodi->nama_prodi,
                    'count' => MahasiswaModel::where('id_prodi', $prodi->id)->count()
                ];
            }),
            'recent_activities' => AktivitasPerkuliahanModel::with(['prodi', 'periode'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get(),
        ];

        return view('admin.dashboard', $data);
    }
}
