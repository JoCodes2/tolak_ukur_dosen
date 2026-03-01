<?php

namespace App\Repositories;

use App\Interfaces\NilaiMahasiswaInterfaces;
use App\Models\AktivitasMengajarDetailModel;
use App\Models\BobotPenilaianDosenModel;
use App\Models\NilaiAkhirMahasiswaModel;
use App\Models\NilaiMahasiswaModel;
use App\Traits\HttpResponseTraits;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class NilaiMahasiswaRepositories implements NilaiMahasiswaInterfaces
{
    use HttpResponseTraits;

    protected $mengajarDetail;
    protected $nilaiMahasiswa;
    protected $nilaiAkhir;
    protected $bobotDosen;

    public function __construct(
        AktivitasMengajarDetailModel $mengajarDetail,
        NilaiMahasiswaModel $nilaiMahasiswa,
        NilaiAkhirMahasiswaModel $nilaiAkhir,
        BobotPenilaianDosenModel $bobotDosen
    ) {
        $this->mengajarDetail = $mengajarDetail;
        $this->nilaiMahasiswa = $nilaiMahasiswa;
        $this->nilaiAkhir = $nilaiAkhir;
        $this->bobotDosen = $bobotDosen;
    }

    public function getDaftarMengajarDosen($idDosen)
    {
        $data = $this->mengajarDetail->with([
            'mataKuliah',
            'aktivitas.periode',
            'aktivitas.prodi',
            'aktivitas.kelas'
        ])->where('id_dosen', $idDosen)->get();

        if ($data->isEmpty()) {
            return $this->dataNotFound();
        }

        return $this->success($data);
    }

    public function getDetailPenilaianKelas($idMengajarDetail)
    {
        try {
            $identitas = $this->mengajarDetail->with([
                'mataKuliah',
                'aktivitas.kelas',
                'aktivitas.prodi',
                'aktivitas.periode',
                'aktivitas.peserta.mahasiswa'
            ])->findOrFail($idMengajarDetail);

            $komponenBobot = $this->bobotDosen->with('komponen')
                ->where('id_mengajar_detail', $idMengajarDetail)
                ->get();

            $idPesertaList = $identitas->aktivitas->peserta->pluck('id');

            $nilaiExisting = $this->nilaiMahasiswa
                ->whereIn('id_peserta', $idPesertaList)
                ->get();

            $isFinal = $this->nilaiAkhir
                ->whereIn('id_peserta', $idPesertaList)
                ->where('id_mk', $identitas->id_mk)
                ->where('status', 'final')
                ->exists();

            return $this->success([
                'identitas' => $identitas,
                'komponen' => $komponenBobot,
                'nilai' => $nilaiExisting,
                'is_final' => $isFinal
            ]);
        } catch (\Throwable $th) {
            return $this->error($th->getMessage(), 400);
        }
    }

    public function simpanNilaiMahasiswa(array $data)
    {
        DB::beginTransaction();
        try {
            foreach ($data['scores'] as $score) {
                $cekFinal = $this->nilaiAkhir
                    ->where('id_peserta', $score['id_peserta'])
                    ->where('status', 'final')
                    ->exists();

                if ($cekFinal) {
                    throw new \Exception("Gagal menyimpan. Beberapa data sudah difinalisasi.");
                }

                $this->nilaiMahasiswa->updateOrCreate(
                    [
                        'id_peserta' => $score['id_peserta'],
                        'id_bobot' => $score['id_bobot'],
                    ],
                    [
                        'id' => Str::uuid(),
                        'nilai' => $score['nilai'] ?? 0
                    ]
                );
            }

            DB::commit();
            return $this->success(null, "Nilai mahasiswa berhasil diperbarui.");
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error($th->getMessage(), 400);
        }
    }

    public function finalisasiNilai($idMengajarDetail)
    {
        DB::beginTransaction();
        try {
            $mengajar = $this->mengajarDetail->with('aktivitas.peserta')->findOrFail($idMengajarDetail);
            $peserta = $mengajar->aktivitas->peserta;

            foreach ($peserta as $p) {
                $this->nilaiAkhir->updateOrCreate(
                    [
                        'id_peserta' => $p->id,
                        'id_mk' => $mengajar->id_mk
                    ],
                    [
                        'id' => Str::uuid(),
                        'status' => 'final',
                        'updated_at' => now()
                    ]
                );
            }

            DB::commit();
            return $this->success(null, "Seluruh nilai kelas ini telah difinalisasi.");
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error($th->getMessage(), 400);
        }
    }
}
