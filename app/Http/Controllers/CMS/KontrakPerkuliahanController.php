<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Repositories\KontrakPerkuliahanRepositories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KontrakPerkuliahanController extends Controller
{
    private $repository;

    public function __construct(KontrakPerkuliahanRepositories $repository)
    {
        $this->repository = $repository;
    }

    public function getKomponen($idMengajarDetail)
    {
        return $this->repository->getKomponenByMengajar($idMengajarDetail);
    }

    public function getBobot($idMengajarDetail)
    {
        return $this->repository->getBobotByMengajar($idMengajarDetail);
    }

    public function syncKomponen($idMengajarDetail)
    {
        return $this->repository->syncKomponen($idMengajarDetail);
    }

    public function storeBobot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_mengajar_detail' => 'required|uuid|exists:aktivitas_mengajar_detail,id',
            'bobot_items' => 'required|array',
            'bobot_items.*.id_komponen' => 'required|uuid|exists:komponen_penilaian_prodi,id',
            'bobot_items.*.bobot' => 'required|numeric|min:0|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        return $this->repository->storeOrUpdateBobot($request->all());
    }
}
