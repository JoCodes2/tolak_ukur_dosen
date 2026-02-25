<?php

namespace App\Interfaces;

use App\Http\Requests\AktivitasPerkuliahanRequest;

interface AktivitasPerkuliahanInterfaces
{
    public function getAllData();
    public function createData(AktivitasPerkuliahanRequest $request);
    public function getDataById($id);
    public function updateData(AktivitasPerkuliahanRequest $request, $id);
    public function deleteData($id);
}
