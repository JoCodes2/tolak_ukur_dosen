<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProdiRequest;
use App\Repositories\ProdiRepositories;

class ProdiController extends Controller
{
    protected $prodiRepo;
    public function __construct(ProdiRepositories $prodiRepo)
    {
        $this->prodiRepo = $prodiRepo;
    }
    public function getAllData()
    {
        return $this->prodiRepo->getAllData();
    }
    public function createData(ProdiRequest $request)
    {
        return $this->prodiRepo->createData($request);
    }
    public function getDataById($id)
    {
        return $this->prodiRepo->getDataById($id);
    }
    public function updateData(ProdiRequest $request, $id)
    {
        return $this->prodiRepo->updateData($request, $id);
    }
    public function deleteData($id)
    {
        return $this->prodiRepo->deleteData($id);
    }
}
