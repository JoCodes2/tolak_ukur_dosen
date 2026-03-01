<?php

namespace App\Repositories;

use App\Interfaces\KontrakPerkuliahanInterfaces;
use App\Models\BobotPenilaianDosenModel;
use App\Models\KomponenPenilaianProdiModel;
use App\Models\AktivitasMengajarDetailModel;
use App\Traits\HttpResponseTraits;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class KontrakPerkuliahanRepositories implements KontrakPerkuliahanInterfaces
{
    use HttpResponseTraits;

    protected $bobotModel;
    protected $mengajarDetail;
    protected $komponenProdi;

    public function __construct(
        BobotPenilaianDosenModel $bobotModel,
        AktivitasMengajarDetailModel $mengajarDetail,
        KomponenPenilaianProdiModel $komponenProdi
    ) {
        $this->bobotModel = $bobotModel;
        $this->mengajarDetail = $mengajarDetail;
        $this->komponenProdi = $komponenProdi;
    }
    public function getMengajarByDosen()
    {
        try {
            $user = Auth::user();

            $query = $this->mengajarDetail::with([
                'aktivitas.periode',
                'aktivitas.prodi',
                'aktivitas.kelas',
                'mataKuliah'
            ]);

            if ($user->role === 'dosen') {
                $query->where('id_dosen', $user->id);
            } else if ($user->role === 'prodi') {
                $query->whereHas('aktivitas', function ($q) use ($user) {
                    $q->where('id_prodi', $user->id_prodi);
                });
            }

            $data = $query->latest()->get();

            return $this->success($data);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400);
        }
    }

    public function getKomponenByMengajar($idMengajarDetail)
    {
        $identitas = $this->mengajarDetail->with([
            'dosen',
            'mataKuliah',
            'aktivitas.periode',
            'aktivitas.prodi',
            'aktivitas.kelas'
        ])->findOrFail($idMengajarDetail);

        $bobot = $this->bobotModel->with('komponen')
            ->where('id_mengajar_detail', $idMengajarDetail)
            ->get();

        return $this->success([
            'identitas' => $identitas,
            'bobot'     => $bobot
        ]);
    }

    public function getBobotByMengajar($idMengajarDetail)
    {
        try {
            $data = $this->bobotModel->with('komponen')
                ->where('id_mengajar_detail', $idMengajarDetail)
                ->get();

            return $this->success($data);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400);
        }
    }

    public function storeOrUpdateBobot(array $data)
    {
        DB::beginTransaction();
        try {
            $idMengajarDetail = $data['id_mengajar_detail'];
            $items = $data['bobot_items'];
            $totalBobot = collect($items)->sum('bobot');
            if ($totalBobot != 100) {
                return $this->error("Total bobot harus berjumlah 100%, saat ini: $totalBobot%", 400);
            }

            foreach ($items as $item) {
                $this->bobotModel->updateOrCreate(
                    [
                        'id_mengajar_detail' => $idMengajarDetail,
                        'id_komponen' => $item['id_komponen']
                    ],
                    [
                        'id' => Str::uuid(),
                        'bobot' => $item['bobot']
                    ]
                );
            }

            DB::commit();
            return $this->success(null, "Bobot penilaian berhasil disimpan.");
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error($th->getMessage(), 400);
        }
    }

    public function syncKomponen($idMengajarDetail)
    {
        DB::beginTransaction();
        try {
            $mengajar = $this->mengajarDetail->findOrFail($idMengajarDetail);
            $aktivitas = $mengajar->aktivitas;

            $komponens = $this->komponenProdi
                ->where('id_mk', $mengajar->id_mk)
                ->where('id_prodi', $aktivitas->id_prodi)
                ->where('id_periode', $aktivitas->id_periode)
                ->get();

            foreach ($komponens as $kp) {
                $this->bobotModel->firstOrCreate([
                    'id_mengajar_detail' => $idMengajarDetail,
                    'id_komponen' => $kp->id
                ], [
                    'id' => Str::uuid(),
                    'bobot' => 0
                ]);
            }

            DB::commit();
            $dataBaru = $this->bobotModel->with('komponen')
                ->where('id_mengajar_detail', $idMengajarDetail)
                ->get();

            return $this->success($dataBaru, "Sinkronisasi komponen berhasil.");
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error($th->getMessage(), 400);
        }
    }
}
