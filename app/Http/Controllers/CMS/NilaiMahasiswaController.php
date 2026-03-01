<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Repositories\NilaiMahasiswaRepositories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiMahasiswaController extends Controller
{
    protected $NilaiRepo;

    public function __construct(NilaiMahasiswaRepositories $NilaiRepo)
    {
        $this->NilaiRepo = $NilaiRepo;
    }

    public function getDaftarMengajarDosen()
    {
        $idDosen = Auth::user()->id_dosen;
        return $this->NilaiRepo->getDaftarMengajarDosen($idDosen);
    }

    public function getDetailPenilaianKelas($idMengajarDetail)
    {
        return $this->NilaiRepo->getDetailPenilaianKelas($idMengajarDetail);
    }

    public function simpanNilaiMahasiswa(Request $request)
    {
        $data = $request->only('scores');
        return $this->NilaiRepo->simpanNilaiMahasiswa($data);
    }

    public function finalisasiNilai($idMengajarDetail)
    {
        return $this->NilaiRepo->finalisasiNilai($idMengajarDetail);
    }
}
