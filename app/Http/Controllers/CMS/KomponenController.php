<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\KomponenRequest;
use App\Repositories\KomponenRepositories;
use Illuminate\Http\Request;

class KomponenController extends Controller
{
     protected $KomponenRepo;
    public function __construct(KomponenRepositories $KomponenRepo)
    {
        $this->KomponenRepo = $KomponenRepo;
    }
    public function getAllData()
    {
        return $this->KomponenRepo->getAllData();
    }
    public function createData(KomponenRequest $request)
    {
        return $this->KomponenRepo->createData($request);
    }
    public function getDataById($id)
    {
        return $this->KomponenRepo->getDataById($id);
    }
    public function updateData(KomponenRequest $request, $id)
    {
        return $this->KomponenRepo->updateData($request, $id);
    }
    public function deleteData($id)
    {
        return $this->KomponenRepo->deleteData($id);
    }
}
