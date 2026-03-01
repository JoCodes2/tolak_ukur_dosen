<?php

namespace App\Interfaces;

interface NilaiMahasiswaInterfaces
{
    public function getDaftarMengajarDosen($idDosen);
    public function getDetailPenilaianKelas($idMengajarDetail);
    public function simpanNilaiMahasiswa(array $data);
    public function finalisasiNilai($idMengajarDetail);
}
