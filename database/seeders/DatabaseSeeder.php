<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Data;
use App\Models\Karyawan;
use App\Models\Kriteria;
use App\Models\SubKriteria;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        User::create([
            'name' => 'Admin',
            'nip' => 'Admin',
            'role' => 'Admin',
            'username' => 'admin',
            'password' => Hash::make('123'),
        ]);

        $karyawan = [
            [
                'nama' => 'Anwar Zemmi',
                'divisi' => 'Kantor'
            ],
            [
                'nama' => 'Faisal Riza',
                'divisi' => 'Lapangan'
            ],
            [
                'nama' => 'M. Lendra',
                'divisi' => 'Kantor'
            ],
            [
                'nama' => 'Nicolas Alex',
                'divisi' => 'Lapangan'
            ],
        ];

        foreach ($karyawan as $k) {
            Karyawan::create([
                'nama' => $k['nama'],
                'divisi' => $k['divisi'],
            ]);
        }

        $kriteria = [
            [
                'nama_kriteria' => 'Kehadiran',
                'bobot' => '27.40',
                'nilai' => '100'
            ],
            [
                'nama_kriteria' => 'Kinerja',
                'bobot' => '24.66',
                'nilai' => '100'
            ],
            [
                'nama_kriteria' => 'Tanggung Jawab',
                'bobot' => '24.66',
                'nilai' => '100'
            ],
            [
                'nama_kriteria' => 'Sikap',
                'bobot' => '23.29',
                'nilai' => '100'
            ],
        ];

        foreach ($kriteria as $k) {
            Kriteria::create([
                'nama_kriteria' => $k['nama_kriteria'],
                'bobot' => $k['bobot'],
                'nilai' => $k['nilai'],
            ]);
        }

        $sub_kriteria = [
            [
                'nama_sub_kriteria' => 'Tepat Waktu',
                'bobot' => '30',
                'kriteria_id' => '1',
            ],
            [
                'nama_sub_kriteria' => 'Total Jam Kerja',
                'bobot' => '40',
                'kriteria_id' => '1',
            ],
            [
                'nama_sub_kriteria' => 'Izin Kerja',
                'bobot' => '30',
                'kriteria_id' => '1',
            ],
            [
                'nama_sub_kriteria' => 'Inisiatif Karyawan',
                'bobot' => '50',
                'kriteria_id' => '2',
            ],
            [
                'nama_sub_kriteria' => 'Kolaborasi Karyawan',
                'bobot' => '50',
                'kriteria_id' => '2',
            ],
            [
                'nama_sub_kriteria' => 'Tanggung Jawab',
                'bobot' => '100',
                'kriteria_id' => '3',
            ],
            [
                'nama_sub_kriteria' => 'Komunikasi',
                'bobot' => '50',
                'kriteria_id' => '4',
            ],
            [
                'nama_sub_kriteria' => 'kejujuran',
                'bobot' => '50',
                'kriteria_id' => '4',
            ],
        ];

        foreach ($sub_kriteria as $sk) {
            SubKriteria::create([
                'nama_sub_kriteria' => $sk['nama_sub_kriteria'],
                'bobot' => $sk['bobot'],
                'kriteria_id' => $sk['kriteria_id'],
            ]);
        }
    }
}
