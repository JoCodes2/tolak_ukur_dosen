<?php

namespace App\Repositories;

use App\Http\Requests\PeriodeRequest;
use App\Interfaces\PeriodeInterfaces;
use App\Models\PeriodeModel;
use App\Traits\HttpResponseTraits;

class PeriodeRepositories implements PeriodeInterfaces
{
    use HttpResponseTraits;

    protected $PeriodeModel;
    public function __construct(PeriodeModel $PeriodeModel)
    {
        $this->PeriodeModel = $PeriodeModel;
    }
    public function getAllData()
    {
        $data = $this->PeriodeModel->all();

        if ($data->isEmpty()) {
            return $this->dataNotFound();
        }

        return $this->success($data);
    }

    public function createData(PeriodeRequest $request)
    {
        try {
            $data = $this->PeriodeModel->create($request->all());
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
        $data = $this->PeriodeModel->find($id);
        if (!$data) {
            return $this->idOrDataNotFound();
        }
        return $this->success($data);
    }
    public function updateData(PeriodeRequest $request, $id)
    {
        try {
            $data = $this->PeriodeModel->find($id);
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
        $data = $this->PeriodeModel->find($id);
        if (!$data) {
            return $this->idOrDataNotFound();
        }
        $data->delete();
        return $this->delete();
    }
}
