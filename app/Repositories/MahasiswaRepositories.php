<?php

namespace App\Repositories;

use App\Http\Requests\MahasiswaRequest;
use App\Interfaces\MahasiswaInterfaces;
use App\Models\MahasiswaModel;
use App\Traits\HttpResponseTraits;

class MahasiswaRepositories implements MahasiswaInterfaces
{
    use HttpResponseTraits;

    protected $MahasiswaModel;
    public function __construct(MahasiswaModel $MahasiswaModel)
    {
        $this->MahasiswaModel = $MahasiswaModel;
    }
    public function getAllData()
    {
        $data = $this->MahasiswaModel->all();

        if ($data->isEmpty()) {
            return $this->dataNotFound();
        }

        return $this->success($data);
    }

    public function createData(MahasiswaRequest $request)
    {
        try {
            $data = $this->MahasiswaModel->create($request->all());
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
        $data = $this->MahasiswaModel->find($id);
        if (!$data) {
            return $this->idOrDataNotFound();
        }
        return $this->success($data);
    }
    public function updateData(MahasiswaRequest $request, $id)
    {
        try {
            $data = $this->MahasiswaModel->find($id);
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
        $data = $this->MahasiswaModel->find($id);
        if (!$data) {
            return $this->idOrDataNotFound();
        }
        $data->delete();
        return $this->delete();
    }
}
