<?php

namespace App\Interfaces;

use App\Http\Requests\KelasRequest;

interface KelasInterfaces
{
    public function getAllData();
    public function createData(KelasRequest $request);
    public function getDataById($id);
    public function updateData(KelasRequest $request, $id);
    public function deleteData($id);
}
