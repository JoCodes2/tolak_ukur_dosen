<?php

namespace App\Http\Controllers\CMS;

use App\Http\Controllers\Controller;
use App\Repositories\AktivitasMengajarDosenRepositories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AktivitasMengajarController extends Controller
{
    private $repository;

    public function __construct(AktivitasMengajarDosenRepositories $repository)
    {
        $this->repository = $repository;
    }

    public function getMengajarByAktivitas($idAktivitas)
    {
        return $this->repository->getMengajarByAktivitas($idAktivitas);
    }

    public function getDropdownMaster()
    {
        return $this->repository->getDropdownMaster();
    }

    public function storePenugasan(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_aktivitas' => 'required|uuid|exists:aktivitas_perkuliahan,id',
            'id_mk'        => 'required|uuid|exists:mata_kuliah,id',
            'id_dosen'     => 'required|uuid|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        return $this->repository->storePenugasan($request->all());
    }

    public function deletePenugasan($id)
    {
        return $this->repository->deletePenugasan($id);
    }
}
