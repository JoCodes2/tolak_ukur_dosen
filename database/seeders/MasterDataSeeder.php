<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class MasterDataSeeder extends Seeder
{
    public function run(): void
    {
        $prodiTiId = Str::uuid();
        $prodiSiId = Str::uuid();

        DB::table('program_studi')->insert([
            ['id' => $prodiTiId, 'kode_prodi' => 'TI', 'nama_prodi' => 'Teknik Informatika', 'created_at' => now()],
            ['id' => $prodiSiId, 'kode_prodi' => 'SI', 'nama_prodi' => 'Sistem Informasi', 'created_at' => now()],
        ]);

        DB::table('kelas')->insert([
            ['id' => Str::uuid(), 'nama_kelas' => 'TI 7.1', 'created_at' => now()],
            ['id' => Str::uuid(), 'nama_kelas' => 'TI 7.2', 'created_at' => now()],
            ['id' => Str::uuid(), 'nama_kelas' => 'TI 7.3', 'created_at' => now()],
            ['id' => Str::uuid(), 'nama_kelas' => 'TI 7.4', 'created_at' => now()],
            ['id' => Str::uuid(), 'nama_kelas' => 'SI 7.1', 'created_at' => now()],
            ['id' => Str::uuid(), 'nama_kelas' => 'SI 7.2', 'created_at' => now()],
        ]);
        $periodeId = Str::uuid();
        DB::table('periode')->insert([
            [
                'id' => $periodeId,
                'nama' => 'Ganjil 7 - 2025/2026',
                'semester' => 7,
                'tahun_ajaran' => '2025/2026',
                'status' => 'aktif',
                'created_at' => now(),
            ]
        ]);

        DB::table('users')->insert([
            ['id' => Str::uuid(), 'nama' => 'Admin Sistem', 'email' => 'admin@stmikadhiguna.ac.id', 'nidn' => null, 'jabatan' => null, 'password' => Hash::make('password'), 'role' => 'admin', 'id_prodi' => null, 'created_at' => now()],
            ['id' => Str::uuid(), 'nama' => 'Ir. Wildan, S.Kom., M.Kom.', 'email' => 'wildan@stmikadhiguna.ac.id', 'nidn' => '0901017001', 'jabatan' => 'Lektor', 'password' => Hash::make('password'), 'role' => 'prodi', 'id_prodi' => $prodiTiId, 'created_at' => now()],
            ['id' => Str::uuid(), 'nama' => 'Nur Alimudin Kaharu, S.Kom., M.Kom.', 'email' => 'alinudin@stmikadhiguna.ac.id', 'nidn' => '0901018002', 'jabatan' => 'Lektor', 'password' => Hash::make('password'), 'role' => 'prodi', 'id_prodi' => $prodiSiId, 'created_at' => now()],
            ['id' => Str::uuid(), 'nama' => 'Emil Hardiansyah, S.Kom., M.Kom', 'email' => 'emil@stmikadhiguna.ac.id', 'nidn' => '57201701', 'jabatan' => 'Tenaga Pengajar', 'password' => Hash::make('password'), 'role' => 'dosen', 'id_prodi' => null, 'created_at' => now()],
            ['id' => Str::uuid(), 'nama' => 'Moh. Kharis, S.Kom., M.Kom', 'email' => 'kharis@stmikadhiguna.ac.id', 'nidn' => '57201702', 'jabatan' => 'Tenaga Pengajar', 'password' => Hash::make('password'), 'role' => 'dosen', 'id_prodi' => null, 'created_at' => now()],
            ['id' => Str::uuid(), 'nama' => 'Isdar A Djufri, S.Sos., M.M', 'email' => 'isdar@stmikadhiguna.ac.id', 'nidn' => '55201701', 'jabatan' => 'Tenaga Pengajar', 'password' => Hash::make('password'), 'role' => 'dosen', 'id_prodi' => null, 'created_at' => now()],
            ['id' => Str::uuid(), 'nama' => 'Ayu Hernita, S.Kom., M.Kom', 'email' => 'ayu@stmikadhiguna.ac.id', 'nidn' => '55201702', 'jabatan' => 'Tenaga Pengajar', 'password' => Hash::make('password'), 'role' => 'dosen', 'id_prodi' => null, 'created_at' => now()],
            ['id' => Str::uuid(), 'nama' => 'Agus Romadhona, S.Kom., M.Kom', 'email' => 'agus@stmikadhiguna.ac.id', 'nidn' => '55201703', 'jabatan' => 'Tenaga Pengajar', 'password' => Hash::make('password'), 'role' => 'dosen', 'id_prodi' => null, 'created_at' => now()],
            ['id' => Str::uuid(), 'nama' => 'Dr. Dewi Kusumawati, M.Kom', 'email' => 'dewi@stmikadhiguna.ac.id', 'nidn' => '55201704', 'jabatan' => 'Tenaga Pengajar', 'password' => Hash::make('password'), 'role' => 'dosen', 'id_prodi' => null, 'created_at' => now()],
        ]);

        // 3. DATA MATA KULIAH (Tanpa id_prodi sesuai struktur Anda)
        $mataKuliahs = [
            // SI MK
            ['id' => Str::uuid(), 'kode_mk' => '57201701', 'nama_mk' => 'Keamanan Sistem Informasi', 'sks' => 3, 'target_prodi' => $prodiSiId],
            ['id' => Str::uuid(), 'kode_mk' => '57201702', 'nama_mk' => 'Tata Kelola dan Audit Sistem Informasi', 'sks' => 3, 'target_prodi' => $prodiSiId],
            // TI MK
            ['id' => Str::uuid(), 'kode_mk' => '55201701', 'nama_mk' => 'Technoprener ship', 'sks' => 3, 'target_prodi' => $prodiTiId],
            ['id' => Str::uuid(), 'kode_mk' => '55201702', 'nama_mk' => 'Keamanan Sistem Informasi', 'sks' => 3, 'target_prodi' => $prodiTiId],
            ['id' => Str::uuid(), 'kode_mk' => '55201703', 'nama_mk' => 'Testing dan Implementasi Sistem', 'sks' => 3, 'target_prodi' => $prodiTiId],
            ['id' => Str::uuid(), 'kode_mk' => '55201704', 'nama_mk' => 'Sistem Pakar', 'sks' => 3, 'target_prodi' => $prodiTiId],
        ];

        foreach ($mataKuliahs as $mk) {
            $targetProdi = $mk['target_prodi'];
            unset($mk['target_prodi']);

            DB::table('mata_kuliah')->insert(array_merge($mk, ['created_at' => now()]));

            $komponens = ['Tugas', 'Aktivitas Partisipatif/Kehadiran', 'Ujian Tengah Semester(UTS)', 'Ujian Akhir Semester(UAS)'];

            foreach ($komponens as $kp) {
                DB::table('komponen_penilaian_prodi')->insert([
                    'id' => Str::uuid(),
                    'id_prodi' => $targetProdi,
                    'id_mk' => $mk['id'],
                    'id_periode' => $periodeId,
                    'nama_komponen' => $kp,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        // 6. SEED MAHASISWA SI 7.1
        $mahasiswaSI = [
            ['nim' => '5720122001', 'nama' => 'AGUSTINA'],
            ['nim' => '5720122002', 'nama' => 'FEBRIYANA'],
            ['nim' => '5720122003', 'nama' => 'MUHAMMAD MUNBA\'ITS'],
            ['nim' => '5720122004', 'nama' => 'MOH. RIZAL IBNUHASIM'],
            ['nim' => '5720122005', 'nama' => 'MUHAMMAD NAJAMUDDIN ZUHDI'],
            ['nim' => '5720122006', 'nama' => 'IFRIYANDI'],
            ['nim' => '5720122007', 'nama' => 'LISTI ADITIA'],
            ['nim' => '5720122008', 'nama' => 'MOH. IDRIS'],
            ['nim' => '5720122009', 'nama' => 'SITI HAWA CAHYANI'],
            ['nim' => '5720122010', 'nama' => 'MOH. TRI AGUNG PRASETYO'],
            ['nim' => '5720122011', 'nama' => 'NUR ASTRI'],
            ['nim' => '5720122012', 'nama' => 'URIB ARYA RUISTA'],
            ['nim' => '5720122013', 'nama' => 'AHMAD HAIKAL'],
            ['nim' => '5720122014', 'nama' => 'MUHAMMAD YUGADISTIRA PRATAMA'],
            ['nim' => '5720122015', 'nama' => 'HUMAIRA'],
            ['nim' => '5720122016', 'nama' => 'MARSHEKEL'],
            ['nim' => '5720122017', 'nama' => 'ABDUL HAKAM'],
            ['nim' => '5720122018', 'nama' => 'LUXSIAN AYUNITA RANGKA'],
            ['nim' => '5720122019', 'nama' => 'RAHMAT'],
            ['nim' => '5720122020', 'nama' => 'SELA SEFTIANI'],
            ['nim' => '5720122021', 'nama' => 'DEDI SETIAWAN'],
            ['nim' => '5720122022', 'nama' => 'ROLLAND ASKENAS SURO'],
            ['nim' => '5720122023', 'nama' => 'ANUGRAH PERSADA Z. MAJID'],
            ['nim' => '5720122050', 'nama' => 'KETUT DEDI'],
            ['nim' => '5720122051', 'nama' => 'RAFLI']
        ];

        foreach ($mahasiswaSI as $mhs) {
            DB::table('mahasiswa')->insert(['id' => Str::uuid(), 'id_prodi' => $prodiSiId, 'nim' => $mhs['nim'], 'nama' => $mhs['nama'], 'angkatan' => 2022, 'created_at' => now()]);
        }

        // 7. SEED MAHASISWA TI 7.1
        $mahasiswaTI = [
            ['nim' => '5520122001', 'nama' => 'FATHUR MAULANA'],
            ['nim' => '5520122005', 'nama' => 'RESKI ILHAM'],
            ['nim' => '5520122011', 'nama' => 'SUCI'],
            ['nim' => '5520122017', 'nama' => 'FITRIA AZHARI SOKO'],
            ['nim' => '5520122023', 'nama' => 'I WAYAN SURYA'],
            ['nim' => '5520122021', 'nama' => 'Farhan Ariq Fedayeen'],
            ['nim' => '5520122029', 'nama' => 'MUHAMMAD ADITYA DZAKIYANTO'],
            ['nim' => '5520122033', 'nama' => 'VISAKA DEWI. R'],
            ['nim' => '5520122037', 'nama' => 'MOH. RISKI BAYU TRIMURTI'],
            ['nim' => '5520122041', 'nama' => 'RACHMAT WIJAYA'],
            ['nim' => '5520122045', 'nama' => 'TRINITY TABITA MAQDELEN'],
            ['nim' => '5520122049', 'nama' => 'I GEDE ALLDO HENDRAWINATA'],
            ['nim' => '5520122053', 'nama' => 'I MADE SATRIA WIRAJAYA'],
            ['nim' => '5520122057', 'nama' => 'FALASTRI NURVITA HAWIA'],
            ['nim' => '5520122062', 'nama' => 'M. VADHEL RAMADHAN'],
            ['nim' => '5520122066', 'nama' => 'PUTRI INASYA KHAIRUNNISA PIU'],
            ['nim' => '5520122070', 'nama' => 'ALFA TIARA ENGKA'],
            ['nim' => '5520122074', 'nama' => 'ABD HALIM'],
            ['nim' => '5520122078', 'nama' => 'ADITYA PUTRA'],
            ['nim' => '5520122082', 'nama' => 'MUH. ANDRI'],
            ['nim' => '5520122087', 'nama' => 'ELIZA MARCELINA SOMBOLAYUK'],
            ['nim' => '5520122091', 'nama' => 'MUH. GILANG RAMADHAN'],
            ['nim' => '5520122095', 'nama' => 'SITI NURHALIZA'],
            ['nim' => '5520122099', 'nama' => 'I GEDE AKIRA ISWARA'],
            ['nim' => '5520122104', 'nama' => 'I KADEK MASANTO'],
            ['nim' => '5520122109', 'nama' => 'FADILAH AULIA'],
            ['nim' => '5520122113', 'nama' => 'TASYA'],
            ['nim' => '5520122123', 'nama' => 'IRFAN AYYUB'],
        ];

        foreach ($mahasiswaTI as $mhs) {
            DB::table('mahasiswa')->insert(['id' => Str::uuid(), 'id_prodi' => $prodiTiId, 'nim' => $mhs['nim'], 'nama' => $mhs['nama'], 'angkatan' => 2022, 'created_at' => now()]);
        }
    }
}
