<?php

namespace App\Interfaces;

use App\Http\Requests\MataKuliahRequest;

interface MataKuliahInterfaces
{
    public function getAllData();
    public function createData(MataKuliahRequest $request);
    public function getDataById($id);
    public function updateData(MataKuliahRequest $request, $id);
    public function deleteData($id);
}
