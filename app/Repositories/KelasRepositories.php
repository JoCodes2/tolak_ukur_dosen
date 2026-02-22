<?php

namespace App\Repositories;

use App\Http\Requests\KelasRequest;
use App\Http\Requests\ProdiRequest;
use App\Interfaces\KelasInterfaces;
use App\Interfaces\ProdiInterfaces;
use App\Models\KelasModel;
use App\Models\ProdiModel;
use App\Traits\HttpResponseTraits;

class KelasRepositories implements KelasInterfaces
{
    use HttpResponseTraits;

    protected $KelasModel;
    public function __construct(KelasModel $KelasModel)
    {
        $this->KelasModel = $KelasModel;
    }
    public function getAllData()
    {
        $data = $this->KelasModel->all();

        if ($data->isEmpty()) {
            return $this->dataNotFound();
        }

        return $this->success($data);
    }

    public function createData(KelasRequest $request)
    {
        try {
            $data = $this->KelasModel->create($request->all());
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
        $data = $this->KelasModel->find($id);
        if (!$data) {
            return $this->idOrDataNotFound();
        }
        return $this->success($data);
    }
    public function updateData(KelasRequest $request, $id)
    {
        try {
            $data = $this->KelasModel->find($id);
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
        $data = $this->KelasModel->find($id);
        if (!$data) {
            return $this->idOrDataNotFound();
        }
        $data->delete();
        return $this->delete();
    }
}
