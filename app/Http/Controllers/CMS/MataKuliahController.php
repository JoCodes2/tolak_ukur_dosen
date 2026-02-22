<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\MataKuliahRequest;
use App\Repositories\MataKuliahRepositories;
use Illuminate\Http\Request;

class MataKuliahController extends Controller
{
    protected $MataKuliahRepo;
    public function __construct(MataKuliahRepositories $MataKuliahRepo)
    {
        $this->MataKuliahRepo = $MataKuliahRepo;
    }
    public function getAllData()
    {
        return $this->MataKuliahRepo->getAllData();
    }
    public function createData(MataKuliahRequest $request)
    {
        return $this->MataKuliahRepo->createData($request);
    }
    public function getDataById($id)
    {
        return $this->MataKuliahRepo->getDataById($id);
    }
    public function updateData(MataKuliahRequest $request, $id)
    {
        return $this->MataKuliahRepo->updateData($request, $id);
    }
    public function deleteData($id)
    {
        return $this->MataKuliahRepo->deleteData($id);
    }
}
