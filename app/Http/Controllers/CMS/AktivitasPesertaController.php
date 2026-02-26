<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\AktivitasPerkuliahanDetailRepositories;

class AktivitasPesertaController extends Controller
{
    protected $aktivitasPeserta;

    public function __construct(AktivitasPerkuliahanDetailRepositories $aktivitasPeserta)
    {
        $this->aktivitasPeserta = $aktivitasPeserta;
    }

    public function getPeserta($idAktivitas)
    {
        return $this->aktivitasPeserta->getPesertaByAktivitas($idAktivitas);
    }

    public function getMahasiswaTersedia(Request $request)
    {
        $idAktivitas = $request->query('id_aktivitas');
        $idProdi = $request->query('id_prodi');

        return $this->aktivitasPeserta->getMahasiswaTersedia($idAktivitas, $idProdi);
    }

    public function storeKolektif(Request $request)
    {
        $data = [
            'id_aktivitas' => $request->id_aktivitas,
            'mahasiswa_ids' => $request->mahasiswa_ids
        ];

        return $this->aktivitasPeserta->storePesertaKolektif($data);
    }

    public function deleteData($id)
    {
        return $this->aktivitasPeserta->deletePeserta($id);
    }
}
