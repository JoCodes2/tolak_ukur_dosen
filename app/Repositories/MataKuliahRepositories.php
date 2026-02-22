<?php

namespace App\Repositories;

use App\Http\Requests\MataKuliahRequest;
use App\Interfaces\MataKuliahInterfaces;
use App\Models\MataKuliahModel;
use App\Traits\HttpResponseTraits;

class MataKuliahRepositories implements MataKuliahInterfaces
{
    use HttpResponseTraits;

    protected $MataKuliahModel;
    public function __construct(MataKuliahModel $MataKuliahModel)
    {
        $this->MataKuliahModel = $MataKuliahModel;
    }
    public function getAllData()
    {
        $data = $this->MataKuliahModel->all();

        if ($data->isEmpty()) {
            return $this->dataNotFound();
        }

        return $this->success($data);
    }

    public function createData(MataKuliahRequest $request)
    {
        try {
            $data = $this->MataKuliahModel->create($request->all());
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
        $data = $this->MataKuliahModel->find($id);
        if (!$data) {
            return $this->idOrDataNotFound();
        }
        return $this->success($data);
    }
    public function updateData(MataKuliahRequest $request, $id)
    {
        try {
            $data = $this->MataKuliahModel->find($id);
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
        $data = $this->MataKuliahModel->find($id);
        if (!$data) {
            return $this->idOrDataNotFound();
        }
        $data->delete();
        return $this->delete();
    }
}
