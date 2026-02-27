<?php

namespace App\Interfaces;

use App\Http\Requests\KomponenRequest;

interface komponenInterfaces
{
    public function getAllData();
    public function createData(KomponenRequest $request);
    public function getDataById($id);
    public function updateData(KomponenRequest $request, $id);
    public function deleteData($id);
}
