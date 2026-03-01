<?php

namespace App\Repositories;

use App\Interfaces\NilaiMahasiswaInterfaces;
use App\Models\AktivitasMengajarDetailModel;
use App\Models\BobotPenilaianDosenModel;
use App\Models\NilaiAkhirMahasiswaModel;
use App\Models\NilaiMahasiswaModel;
use App\Traits\HttpResponseTraits;
use Illuminate\Support\Facades\Auth;
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

    public function getDaftarMengajarDosen($idDosen = null)
    {
        $user = Auth::user();
        if (!$user) {
            return $this->error("Unauthorized", 401);
        }

        $query = $this->mengajarDetail->with([
            'mataKuliah',
            'aktivitas.periode',
            'aktivitas.prodi',
            'aktivitas.kelas'
        ]);

        if ($user->role === 'dosen') {
            $query->where('id_dosen', $user->id);
        } elseif ($user->role === 'prodi') {
            $query->whereHas('aktivitas', function ($q) use ($user) {
                $q->where('id_prodi', $user->id_prodi);
            });
        }

        $data = $query->get();

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
                'dosen',
                'aktivitas.kelas',
                'aktivitas.prodi',
                'aktivitas.periode',
                'aktivitas.pesertaDetail.mahasiswa'
            ])->findOrFail($idMengajarDetail);

            $pesertaDetail = $identitas->aktivitas->pesertaDetail ?? collect([]);

            $idPesertaList = $pesertaDetail->pluck('id');

            $komponenBobot = $this->bobotDosen->with('komponen')
                ->where('id_mengajar_detail', $idMengajarDetail)
                ->get();

            $nilaiExisting = $this->nilaiMahasiswa
                ->whereIn('id_peserta', $idPesertaList)
                ->get();

            $isFinal = false;
            if ($idPesertaList->isNotEmpty()) {
                $isFinal = $this->nilaiAkhir
                    ->whereIn('id_peserta', $idPesertaList)
                    ->where('id_mk', $identitas->id_mk)
                    ->where('status', 'final')
                    ->exists();
            }

            return $this->success([
                'identitas' => $identitas,
                'komponen' => $komponenBobot,
                'nilai' => $nilaiExisting,
                'is_final' => $isFinal
            ]);
        } catch (\Throwable $th) {
            return $this->error("Gagal memuat detail: " . $th->getMessage(), 400);
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
            $mengajar = $this->mengajarDetail->with([
                'aktivitas.pesertaDetail.mahasiswa',
                'bobotPenilaian'
            ])->findOrFail($idMengajarDetail);

            $peserta = $mengajar->aktivitas->pesertaDetail;
            $bobotList = $mengajar->bobotPenilaian;

            if (!$bobotList || $bobotList->isEmpty()) {
                throw new \Exception("Komponen bobot penilaian belum diatur untuk kelas ini.");
            }

            $idBobots = $bobotList->pluck('id')->toArray();

            foreach ($peserta as $p) {
                $nilaiKomponen = $this->nilaiMahasiswa
                    ->where('id_peserta', $p->id)
                    ->whereIn('id_bobot', $idBobots)
                    ->get();

                $totalAngka = 0;
                foreach ($bobotList as $b) {
                    $n = $nilaiKomponen->where('id_bobot', $b->id)->first();
                    $skor = $n ? (float)$n->nilai : 0;
                    $totalAngka += ($skor * (float)$b->bobot / 100);
                }

                $hasil = $this->konversiNilai($totalAngka);

                $this->nilaiAkhir->updateOrCreate(
                    [
                        'id_peserta' => $p->id,
                        'id_mk'      => $mengajar->id_mk
                    ],
                    [
                        'nilai_angka' => $totalAngka,
                        'nilai_huruf' => $hasil['huruf'],
                        'bobot_mutu'  => $hasil['indeks'],
                        'status'      => 'final',
                        'updated_at'  => now()
                    ]
                );
            }

            $mengajar->update(['is_final' => true]);

            DB::commit();
            return $this->success(null, "Seluruh nilai kelas ini telah difinalisasi.");
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->error($th->getMessage(), 400);
        }
    }
    private function konversiNilai($score)
    {
        if ($score >= 85) return ['huruf' => 'A',  'indeks' => 4.00];
        if ($score >= 80) return ['huruf' => 'A-', 'indeks' => 3.75];
        if ($score >= 75) return ['huruf' => 'B+', 'indeks' => 3.50];
        if ($score >= 70) return ['huruf' => 'B',  'indeks' => 3.00];
        if ($score >= 65) return ['huruf' => 'B-', 'indeks' => 2.75];
        if ($score >= 60) return ['huruf' => 'C+', 'indeks' => 2.50];
        if ($score >= 55) return ['huruf' => 'C',  'indeks' => 2.00];
        if ($score >= 50) return ['huruf' => 'D',  'indeks' => 1.00];
        return ['huruf' => 'E', 'indeks' => 0.00];
    }
}
