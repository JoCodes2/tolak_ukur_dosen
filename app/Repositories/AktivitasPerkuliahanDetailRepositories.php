<?php

namespace App\Repositories;

use App\Interfaces\AktivitasPerkuliahanDetailInterfaces;
use App\Models\AktivitasPerkuliahanModel;
use App\Models\AktivitasPesertaDetailModel;
use App\Models\MahasiswaModel;
use App\Traits\HttpResponseTraits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AktivitasPerkuliahanDetailRepositories implements AktivitasPerkuliahanDetailInterfaces
{
    use HttpResponseTraits;

    protected $aktivitasPerserta;
    protected $mahasiswaModel;

    public function __construct(
        AktivitasPesertaDetailModel $aktivitasPerserta,
        MahasiswaModel $mahasiswaModel
    ) {
        $this->aktivitasPerserta = $aktivitasPerserta;
        $this->mahasiswaModel = $mahasiswaModel;
    }

    public function getPesertaByAktivitas($idAktivitas)
    {
        $data = $this->aktivitasPerserta->where('id_aktivitas', $idAktivitas)
            ->with('mahasiswa')
            ->get();

        if ($data->isEmpty()) {
            return $this->dataNotFound();
        }

        return $this->success($data);
    }

    public function getMahasiswaTersedia($idAktivitas, $idProdi)
    {
        $aktivitas = AktivitasPerkuliahanModel::find($idAktivitas);

        if (!$aktivitas) {
            return $this->error("Aktivitas tidak ditemukan", 404);
        }

        $idPeriode = $aktivitas->id_periode;
        $mahasiswaSudahKuliah = $this->aktivitasPerserta
            ->whereHas('aktivitas', function ($query) use ($idPeriode) {
                $query->where('id_periode', $idPeriode);
            })
            ->pluck('id_mahasiswa');

        $data = $this->mahasiswaModel->where('id_prodi', $idProdi)
            ->whereNotIn('id', $mahasiswaSudahKuliah)
            ->get();

        if ($data->isEmpty()) {
            return $this->dataNotFound("Semua mahasiswa di prodi ini sudah terdaftar pada periode tersebut.");
        }

        return $this->success($data);
    }

    public function storePesertaKolektif(array $data)
    {
        DB::beginTransaction();
        try {
            $idAktivitas = $data['id_aktivitas'];

            $mahasiswaIds = $data['mahasiswa_ids'] ?? [];
            if (is_string($mahasiswaIds)) {
                $mahasiswaIds = json_decode($mahasiswaIds, true);
            }

            if (empty($mahasiswaIds) || !is_array($mahasiswaIds)) {
                return $this->error("Tidak ada mahasiswa yang dipilih.", 400);
            }
            if (empty($mahasiswaIds)) {
                return $this->error("Tidak ada mahasiswa yang dipilih.", 400);
            }

            $payload = [];
            foreach ($mahasiswaIds as $idMhs) {
                $payload[] = [
                    'id' => Str::uuid(),
                    'id_aktivitas' => $idAktivitas,
                    'id_mahasiswa' => $idMhs,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $this->aktivitasPerserta->insert($payload);

            DB::commit();
            return $this->success($payload, "Berhasil menambahkan mahasiswa secara kolektif");
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error(
                $th->getMessage(),
                400,
                $th,
                class_basename($this),
                __FUNCTION__
            );
        }
    }

    public function deletePeserta($id)
    {
        $data = $this->aktivitasPerserta->find($id);

        if (!$data) {
            return $this->idOrDataNotFound();
        }

        try {
            $data->delete();
            return $this->delete();
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
}
