<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\KelasRequest;
use App\Repositories\KelasRepositories;
use Illuminate\Http\Request;

class KelasController extends Controller
{
     protected $KelasRepo;
    public function __construct(KelasRepositories $KelasRepo)
    {
        $this->KelasRepo = $KelasRepo;
    }
    public function getAllData()
    {
        return $this->KelasRepo->getAllData();
    }
    public function createData(KelasRequest $request)
    {
        return $this->KelasRepo->createData($request);
    }
    public function getDataById($id)
    {
        return $this->KelasRepo->getDataById($id);
    }
    public function updateData(KelasRequest $request, $id)
    {
        return $this->KelasRepo->updateData($request, $id);
    }
    public function deleteData($id)
    {
        return $this->KelasRepo->deleteData($id);
    }
}
