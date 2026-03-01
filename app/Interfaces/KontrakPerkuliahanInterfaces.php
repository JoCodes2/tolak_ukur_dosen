<?php

namespace App\Interfaces;

interface KontrakPerkuliahanInterfaces
{
    public function getKomponenByMengajar($idMengajarDetail);

    public function getBobotByMengajar($idMengajarDetail);

    public function storeOrUpdateBobot(array $data);

    public function syncKomponen($idMengajarDetail);
    public function getMengajarByDosen();
}
