<?php

namespace App\Interfaces;

interface AktivitasMengajarInterfaces
{
    public function getMengajarByAktivitas($idAktivitas);

    public function storePenugasan(array $data);

    public function deletePenugasan($id);

    public function getDropdownMaster();
}
