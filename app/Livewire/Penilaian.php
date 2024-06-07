<?php

namespace App\Livewire;

use App\Models\Karyawan;
use App\Models\Kriteria;
use App\Models\PenilaianDb;
use App\Models\SubKriteria;
use Livewire\Component;

class Penilaian extends Component
{
    public $karyawans;
    public $id_karyawan = [];
    public $nama_karyawan = [];
    public $divisi_karyawan = [];
    public $sub_kriteria = [];
    public $tgl_penilaian = 'p';
    public $bobot = []; // Tambahkan properti $bobot

    public function mount()
    {
        $this->karyawans = Karyawan::all();
        $this->sub_kriteria = SubKriteria::all();

        foreach ($this->karyawans as $karyawan) {
            $this->id_karyawan[$karyawan->id] = $karyawan->id;
            $this->nama_karyawan[$karyawan->id] = $karyawan->nama;
            $this->divisi_karyawan[$karyawan->id] = $karyawan->divisi;
        }
    }

    public function render()
    {
        return view('livewire.penilaian');
    }

    public function simpan()
    {
        foreach ($this->karyawans as $sub) {
            // Buat objek Penilaian
            $penilaian = new PenilaianDb();
            // Tetapkan properti karyawan_id
            $penilaian->karyawan_id = $sub->id;
            // Tetapkan properti tgl_penilaian
            $penilaian->tgl_penilaian = $this->tgl_penilaian;

            // Buat array untuk menyimpan bobot
            $bobotArray = [];
            foreach ($this->bobot[$sub->id] as $subKriteriaId => $bobot) {
                // Ambil nama sub-kriteria berdasarkan ID
                $namaSubKriteria = SubKriteria::find($subKriteriaId)->nama_sub_kriteria;
                // Simpan bobot dengan kunci nama sub-kriteria
                $bobotArray[$namaSubKriteria] = $bobot;
            }

            // Tetapkan properti data
            $penilaian->data = json_encode([
                'bobot' => $bobotArray, // Gunakan array yang sudah dibuat
            ]);
            // Simpan penilaian ke dalam database
            $penilaian->save();
        }

        // Reset input
        $this->reset(['tgl_penilaian', 'bobot']);
    }
}
