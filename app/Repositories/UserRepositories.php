<?php

namespace App\Repositories;

use App\Http\Requests\UserRequest;
use App\Interfaces\UserInterfaces;
use App\Models\User;
use App\Traits\HttpResponseTraits;
use Illuminate\Support\Facades\Hash;

class UserRepositories implements UserInterfaces
{
    use HttpResponseTraits;
    protected $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function getAllData()
    {
        $data = $this->userModel->with('prodi')->latest()->get();

        if ($data->isEmpty()) {
            return $this->dataNotFound();
        }

        return $this->success($data);
    }

    public function createData(UserRequest $request)
    {
        try {
            $data = new $this->userModel;
            $data->nama = $request->nama;
            $data->email = $request->email;
            $data->nidn = $request->nidn;
            $data->jabatan = $request->jabatan;
            $data->role = $request->role;

            $data->id_prodi = ($request->role === 'prodi') ? $request->id_prodi : null;

            $data->password = Hash::make($request->password);
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

    public function getDataById($id)
    {
        $data = $this->userModel->with('prodi')->find($id);
        if (!$data) {
            return $this->idOrDataNotFound();
        }
        return $this->success($data);
    }

    public function updateData(UserRequest $request, $id)
    {
        try {
            $data = $this->userModel->find($id);
            if (!$data) {
                return $this->idOrDataNotFound();
            }

            $data->nama = $request->nama;
            $data->email = $request->email;
            $data->nidn = $request->nidn;
            $data->jabatan = $request->jabatan;
            $data->role = $request->role;

            $data->id_prodi = ($request->role === 'prodi') ? $request->id_prodi : null;

            if ($request->filled('password')) {
                $data->password = Hash::make($request->password);
            }

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
        $data = $this->userModel->find($id);
        if (!$data) {
            return $this->idOrDataNotFound();
        }

        $data->delete();
        return $this->delete();
    }
}
