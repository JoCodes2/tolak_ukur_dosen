<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Http\Requests\AktivitasPerkuliahanRequest;
use App\Repositories\AktivitasPerkuliahanRepositories;
use Illuminate\Http\Request;

class AktivitasPerkuliahanController extends Controller
{
    protected $aktivitasPerkuliahan;

    public function __construct(AktivitasPerkuliahanRepositories $aktivitasPerkuliahan)
    {
        $this->aktivitasPerkuliahan = $aktivitasPerkuliahan;
    }
    public function getAllData()
    {
        return $this->aktivitasPerkuliahan->getAllData();
    }
    public function createData(AktivitasPerkuliahanRequest $request)
    {
        return $this->aktivitasPerkuliahan->createData($request);
    }
    public function getDataById($id)
    {
        return $this->aktivitasPerkuliahan->getDataById($id);
    }
    public function updateData(AktivitasPerkuliahanRequest $request, $id)
    {
        return $this->aktivitasPerkuliahan->updateData($request, $id);
    }
    public function deleteData($id)
    {
        return $this->aktivitasPerkuliahan->deleteData($id);
    }
}
