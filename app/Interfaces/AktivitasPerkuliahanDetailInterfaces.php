<?php

namespace App\Interfaces;

interface AktivitasPerkuliahanDetailInterfaces
{
    public function getPesertaByAktivitas($idAktivitas);

    public function getMahasiswaTersedia($idAktivitas, $idProdi);

    public function storePesertaKolektif(array $data);

    public function deletePeserta($id);
}
