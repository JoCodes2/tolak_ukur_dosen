<?php

namespace App\Interfaces;

use App\Http\Requests\PeriodeRequest;

interface PeriodeInterfaces
{
    public function getAllData();
    public function createData(PeriodeRequest $request);
    public function getDataById($id);
    public function updateData(PeriodeRequest $request, $id);
    public function deleteData($id);
}
