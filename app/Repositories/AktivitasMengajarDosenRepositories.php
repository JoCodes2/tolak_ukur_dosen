<?php

namespace App\Repositories;

use App\Interfaces\AktivitasMengajarInterfaces;
use App\Models\AktivitasMengajarDetailModel;
use App\Models\MataKuliahModel;
use App\Models\User;
use App\Traits\HttpResponseTraits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AktivitasMengajarDosenRepositories implements AktivitasMengajarInterfaces
{
    use HttpResponseTraits;

    protected $aktivitasMengajarDosen;

    public function __construct(AktivitasMengajarDetailModel $aktivitasMengajarDosen)
    {
        $this->aktivitasMengajarDosen = $aktivitasMengajarDosen;
    }

    public function getMengajarByAktivitas($idAktivitas)
    {
        try {
            $data = $this->aktivitasMengajarDosen->with(['mataKuliah', 'dosen'])
                ->where('id_aktivitas', $idAktivitas)
                ->get();

            return $this->success($data);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400);
        }
    }

    public function storePenugasan(array $data)
    {
        DB::beginTransaction();
        try {
            $exists = $this->aktivitasMengajarDosen
                ->where('id_aktivitas', $data['id_aktivitas'])
                ->where('id_mk', $data['id_mk'])
                ->where('id_dosen', $data['id_dosen'])
                ->exists();

            if ($exists) {
                return response()->json([
                    'code' => 400,
                    'message' => "Dosen tersebut sudah ditugaskan pada mata kuliah ini."
                ], 400);
            }
            $record = $this->aktivitasMengajarDosen->create([
                'id' => Str::uuid(),
                'id_aktivitas' => $data['id_aktivitas'],
                'id_mk' => $data['id_mk'],
                'id_dosen' => $data['id_dosen'],
            ]);

            DB::commit();
            return $this->success($record, "Penugasan dosen berhasil disimpan.");
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error($th->getMessage(), 400);
        }
    }
    public function deletePenugasan($id)
    {
        try {
            $data = $this->aktivitasMengajarDosen->findOrFail($id);
            $data->delete();

            return $this->delete();
        } catch (\Throwable $th) {
            return $this->error("Gagal menghapus data penugasan.", 400);
        }
    }

    public function getDropdownMaster()
    {
        try {
            $mk = MataKuliahModel::orderBy('nama_mk', 'asc')
                ->get(['id', 'nama_mk', 'kode_mk']);

            $dosen = User::where('role', 'dosen')
                ->orderBy('nama', 'asc')
                ->get(['id', 'nama', 'nidn']);

            return $this->success([
                'mata_kuliah' => $mk,
                'dosen' => $dosen
            ]);
        } catch (\Throwable $th) {
            return $this->error("Gagal mengambil data master: " . $th->getMessage(), 400);
        }
    }
}
