<?php

namespace App\Interfaces;

use App\Http\Requests\ProdiRequest;

interface ProdiInterfaces
{
    public function getAllData();
    public function createData(ProdiRequest $request);
    public function getDataById($id);
    public function updateData(ProdiRequest $request, $id);
    public function deleteData($id);
}
