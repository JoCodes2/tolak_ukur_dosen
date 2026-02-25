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
        // 1. SEED PROGRAM STUDI
        $prodiTiId = Str::uuid();
        $prodiSiId = Str::uuid();

        DB::table('program_studi')->insert([
            [
                'id' => $prodiTiId,
                'kode_prodi' => 'TI',
                'nama_prodi' => 'Teknik Informatika',
                'created_at' => now(),
            ],
            [
                'id' => $prodiSiId,
                'kode_prodi' => 'SI',
                'nama_prodi' => 'Sistem Informasi',
                'created_at' => now(),
            ],
        ]);

        // 2. SEED PERIODE (Semester Ganjil 7 - 2025/2026)
        DB::table('periode')->insert([
            [
                'id' => Str::uuid(),
                'nama' => 'Ganjil 7 - 2025/2026',
                'semester' => 7,
                'tahun_ajaran' => '2025/2026',
                'status' => 'aktif',
                'created_at' => now(),
            ]
        ]);

        // 3. SEED USERS (Pastikan semua baris punya kolom yang sama)
        DB::table('users')->insert([
            [
                'id' => Str::uuid(),
                'nama' => 'Admin Sistem',
                'email' => 'admin@stmikadhiguna.ac.id',
                'nidn' => null, // Harus ditulis
                'jabatan' => null, // Harus ditulis
                'password' => Hash::make('password'),
                'role' => 'admin',
                'id_prodi' => null,
                'created_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'nama' => 'Dr. Ahmad Kaprodi',
                'email' => 'kaprodi_ti@stmikadhiguna.ac.id',
                'nidn' => '0901018801',
                'jabatan' => 'Lektor',
                'password' => Hash::make('password'),
                'role' => 'prodi',
                'id_prodi' => $prodiTiId,
                'created_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'nama' => 'Dosen Pengajar 1',
                'email' => 'dosen@stmikadhiguna.ac.id',
                'nidn' => '0901019002',
                'jabatan' => 'Asisten Ahli',
                'password' => Hash::make('password'),
                'role' => 'dosen',
                'id_prodi' => null,
                'created_at' => now(),
            ],
        ]);

        // 4. SEED MAHASISWA
        DB::table('mahasiswa')->insert([
            [
                'id' => Str::uuid(),
                'id_prodi' => $prodiTiId,
                'nim' => '220101001',
                'nama' => 'Budi Santoso',
                'angkatan' => 2022,
                'created_at' => now(),
            ],
            [
                'id' => Str::uuid(),
                'id_prodi' => $prodiSiId,
                'nim' => '220201050',
                'nama' => 'Siti Aminah',
                'angkatan' => 2022,
                'created_at' => now(),
            ]
        ]);
    }
}
