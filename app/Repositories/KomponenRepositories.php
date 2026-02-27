<?php

namespace App\Repositories;

use App\Http\Requests\KomponenRequest;
use App\Interfaces\komponenInterfaces;
use App\Models\KomponenPenilaianProdiModel;
use App\Traits\HttpResponseTraits;
use Illuminate\Support\Facades\Auth;

class KomponenRepositories implements komponenInterfaces
{
    use HttpResponseTraits;

    protected $KomponenModel;
    public function __construct(KomponenPenilaianProdiModel $KomponenModel)
    {
        $this->KomponenModel = $KomponenModel;
    }
    // public function getAllData()
    // {
    //     $data = $this->KomponenModel->all();

    //     if ($data->isEmpty()) {
    //         return $this->dataNotFound();
    //     }

    //     return $this->success($data);
    // }

    // public function getAllData()
    // {
    //     $data = $this->KomponenModel
    //         ->select('id_prodi', 'id_mk', 'id_periode', 'nama_komponen')
    //         ->get()
    //         ->groupBy(function ($item) {
    //             return $item->id_prodi . '-' . $item->id_mk . '-' . $item->id_periode;
    //         })
    //         ->map(function ($items) {
    //             return [
    //                 'id_prodi' => $items->first()->id_prodi,
    //                 'id_mk' => $items->first()->id_mk,
    //                 'id_periode' => $items->first()->id_periode,
    //                 'nama_komponen' => $items->pluck('nama_komponen')->values(),
    //             ];
    //         })
    //         ->values();

    //     if ($data->isEmpty()) {
    //         return $this->dataNotFound();
    //     }

    //     return $this->success($data);
    // }

    public function getAllData()
    {
        $user = Auth::user();

        $query = $this->KomponenModel
            ->select('id', 'id_prodi', 'id_mk', 'id_periode', 'nama_komponen');

        // Jika role prodi, filter sesuai id_prodi user
        if ($user->role === 'prodi') {
            $query->where('id_prodi', $user->id_prodi);
        }
        // Jika admin, tidak perlu filter (lihat semua)

        $data = $query->get()
            ->groupBy(function ($item) {
                return $item->id_prodi . '-' . $item->id_mk . '-' . $item->id_periode;
            })
            ->map(function ($items) {
                return [
                    'id_prodi'   => $items->first()->id_prodi,
                    'id_mk'      => $items->first()->id_mk,
                    'id_periode' => $items->first()->id_periode,
                    'komponen'   => $items->map(fn($i) => [
                        'id'             => $i->id,
                        'nama_komponen'  => $i->nama_komponen,
                    ])->values(),
                ];
            })
            ->values();

        if ($data->isEmpty()) {
            return $this->dataNotFound();
        }

        return $this->success($data);
    }

    public function createData(KomponenRequest $request)
    {
        try {
            $data = $this->KomponenModel->create($request->all());
            return $this->success($data);
        } catch (\Throwable $th) {
            return $this->error(
                $th->getMessage(),
                400,
                $th,
                class_basename($this),
                __FUNCTION__
            );
        }
    }
    public function getDataById($id)
    {
        $data = $this->KomponenModel->find($id);
        if (!$data) {
            return $this->idOrDataNotFound();
        }
        return $this->success($data);
    }
    public function updateData(KomponenRequest $request, $id)
    {
        try {
            $data = $this->KomponenModel->find($id);
            if (!$data) {
                return $this->idOrDataNotFound();
            }
            $data->update($request->all());
            $data->save();

            return $this->success($data);
        } catch (\Throwable $th) {
            return $this->error(
                $th->getMessage(),
                400,
                $th,
                class_basename($this),
                __FUNCTION__
            );
        }
    }
    public function deleteData($id)
    {
        $data = $this->KomponenModel->find($id);
        if (!$data) {
            return $this->idOrDataNotFound();
        }
        $data->delete();
        return $this->delete();
    }
}
