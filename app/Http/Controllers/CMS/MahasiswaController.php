<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\MahasiswaRequest;
use App\Repositories\MahasiswaRepositories;
use Illuminate\Http\Request;

class MahasiswaController extends Controller
{
    protected $MahasiswaRepo;
    public function __construct(MahasiswaRepositories $MahasiswaRepo)
    {
        $this->MahasiswaRepo = $MahasiswaRepo;
    }
    public function getAllData()
    {
        return $this->MahasiswaRepo->getAllData();
    }
    public function createData(MahasiswaRequest $request)
    {
        return $this->MahasiswaRepo->createData($request);
    }
    public function getDataById($id)
    {
        return $this->MahasiswaRepo->getDataById($id);
    }
    public function updateData(MahasiswaRequest $request, $id)
    {
        return $this->MahasiswaRepo->updateData($request, $id);
    }
    public function deleteData($id)
    {
        return $this->MahasiswaRepo->deleteData($id);
    }
}
