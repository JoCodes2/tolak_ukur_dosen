<?php

namespace App\Repositories;

use App\Http\Requests\ProdiRequest;
use App\Interfaces\ProdiInterfaces;
use App\Models\ProdiModel;
use App\Traits\HttpResponseTraits;

class ProdiRepositories implements ProdiInterfaces
{
    use HttpResponseTraits;

    protected $prodiModel;
    public function __construct(ProdiModel $prodiModel)
    {
        $this->prodiModel = $prodiModel;
    }
    public function getAllData()
    {
        $data = $this->prodiModel->all();

        if ($data->isEmpty()) {
            return $this->dataNotFound();
        }

        return $this->success($data);
    }

    public function createData(ProdiRequest $request)
    {
        try {
            $data = $this->prodiModel->create($request->all());
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
        $data = $this->prodiModel->find($id);
        if (!$data) {
            return $this->idOrDataNotFound();
        }
        return $this->success($data);
    }
    public function updateData(ProdiRequest $request, $id)
    {
        try {
            $data = $this->prodiModel->find($id);
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
        $data = $this->prodiModel->find($id);
        if (!$data) {
            return $this->idOrDataNotFound();
        }
        $data->delete();
        return $this->delete();
    }
}
