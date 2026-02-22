<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\PeriodeRequest;
use App\Repositories\PeriodeRepositories;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    protected $PeriodeRepo;
    public function __construct(PeriodeRepositories $PeriodeRepo)
    {
        $this->PeriodeRepo = $PeriodeRepo;
    }
    public function getAllData()
    {
        return $this->PeriodeRepo->getAllData();
    }
    public function createData(PeriodeRequest $request)
    {
        return $this->PeriodeRepo->createData($request);
    }
    public function getDataById($id)
    {
        return $this->PeriodeRepo->getDataById($id);
    }
    public function updateData(PeriodeRequest $request, $id)
    {
        return $this->PeriodeRepo->updateData($request, $id);
    }
    public function deleteData($id)
    {
        return $this->PeriodeRepo->deleteData($id);
    }
}
