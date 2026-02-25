<?php

namespace App\Repositories;

use App\Http\Requests\AktivitasPerkuliahanRequest;
use App\Interfaces\AktivitasPerkuliahanInterfaces;
use App\Models\AktivitasPerkuliahanModel;
use App\Traits\HttpResponseTraits;

class AktivitasPerkuliahanRepositories implements AktivitasPerkuliahanInterfaces
{
    use HttpResponseTraits;

    protected $aktivitasPerkuliahan;

    public function __construct(AktivitasPerkuliahanModel $aktivitasPerkuliahan)
    {
        $this->aktivitasPerkuliahan = $aktivitasPerkuliahan;
    }

    public function getAllData()
    {
        $data = $this->aktivitasPerkuliahan->with(['periode', 'prodi', 'kelas'])
            ->withCount([
                'mengajarDetail as total_dosen',
                'pesertaDetail as total_mahasiswa'
            ])
            ->latest()
            ->get();


        if ($data->isEmpty()) {
            return $this->dataNotFound();
        }

        return $this->success($data);
    }

    public function getDataById($id)
    {
        $data = $this->aktivitasPerkuliahan->with(['periode', 'prodi', 'kelas'])->find($id);

        if (!$data) {
            return $this->idOrDataNotFound();
        }

        return $this->success($data);
    }

    public function createData(AktivitasPerkuliahanRequest $request)
    {
        try {
            $data = new $this->aktivitasPerkuliahan;
            $data->id_periode = $request->id_periode;
            $data->id_prodi   = $request->id_prodi;
            $data->id_kelas   = $request->id_kelas;
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

    public function updateData(AktivitasPerkuliahanRequest $request, $id)
    {
        try {
            $data = $this->aktivitasPerkuliahan->find($id);
            if (!$data) {
                return $this->idOrDataNotFound();
            }

            $data->id_periode = $request->id_periode;
            $data->id_prodi   = $request->id_prodi;
            $data->id_kelas   = $request->id_kelas;
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
        $data = $this->aktivitasPerkuliahan->find($id);
        if (!$data) {
            return $this->idOrDataNotFound();
        }
        $data->delete();

        return $this->delete();
    }


    public function getDetailAktivitas($id)
    {
        $data = $this->aktivitasPerkuliahan->with([
            'periode',
            'prodi',
            'kelas',
            'pesertaDetail.mahasiswa',
            'mengajarDetail.mataKuliah',
            'mengajarDetail.dosen'
        ])->find($id);

        if (!$data) {
            return $this->idOrDataNotFound();
        }

        return $this->success($data);
    }
}
