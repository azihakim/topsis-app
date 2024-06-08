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

    public $bbt_kehadiran = 27.40;
    public $bbt_kinerja = 24.66;
    public $bbt_tanggung_jawab = 24.66;
    public $bbt_sikap = 23.29;

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
        $semuaPenilaian = [];
        $semuaData = [];
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
                $subKriteria = SubKriteria::find($subKriteriaId);
                $namaKriteria = $subKriteria->kriteria->nama_kriteria;
                $namaSubKriteria = $subKriteria->nama_sub_kriteria;

                // Pastikan kriteria sudah ada di dalam array bobotArray
                if (!isset($bobotArray[$namaKriteria])) {
                    $bobotArray[$namaKriteria] = [];
                }

                // Tambahkan bobot ke sub kriteria yang sesuai
                $bobotArray[$namaKriteria][$namaSubKriteria] = $bobot;
            }

            // Hitung total bobot untuk setiap kriteria
            foreach ($bobotArray as &$kriteria) {
                $total = array_sum($kriteria);
                $kriteria['total'] = $total;
            }
            $penilaian->data = json_encode(['bobot' => $bobotArray]);
            $semuaPenilaian[] = $penilaian;
        }

        foreach ($semuaPenilaian as $penilaian) {
            // Dekode data dari JSON
            $data = json_decode($penilaian->data, true);
            // Tambahkan data ke dalam array $semuaData
            $semuaData[] = $data;
        }
        // dd($semuaData);


        $sumKehadiran = 0;
        $sumKinerja = 0;
        $sumTanggungJawab = 0;
        $sumSikap = 0;

        $sqrtKehadiran = 0;
        $sqrtKinerja = 0;
        $sqrtTanggungJawab = 0;
        $sqrtSikap = 0;


        // Loop melalui semua data
        $dataSemua = $semuaData;
        foreach ($dataSemua as &$item) {
            foreach ($item['bobot'] as &$subCriteria) {
                $subCriteria['total'] = pow((int)$subCriteria['total'], 2);
            }
        }
        foreach ($dataSemua as $data) {
            // Periksa apakah kategori ada dalam data

            if (isset($data['bobot']['Kehadiran']['total'])) {
                // $sumKehadiran += $data['bobot']['Kehadiran']['total'];
                $sumKehadiran += $data['bobot']['Kehadiran']['total'];
                $sqrtKehadiran = sqrt($sumKehadiran);
            }

            if (isset($data['bobot']['Kinerja']['total'])) {
                $sumKinerja += $data['bobot']['Kinerja']['total'];
                $sqrtKinerja = sqrt($sumKinerja);
            }

            if (isset($data['bobot']['Tanggung Jawab']['total'])) {
                $sumTanggungJawab += $data['bobot']['Tanggung Jawab']['total'];
                $sqrtTanggungJawab = sqrt($sumTanggungJawab);
            }

            if (isset($data['bobot']['Sikap']['total'])) {
                $sumSikap += $data['bobot']['Sikap']['total'];
                $sqrtSikap = sqrt($sumSikap);
            }
        }
        // dd($sumKehadiran, $sumKinerja, $sumTanggungJawab, $sumSikap);
        dd($sqrtKehadiran, $sqrtKinerja, $sqrtTanggungJawab, $sqrtSikap);

        // $penilaian->data = json_encode(['bobot' => $bobotArray, 'total' => $hasilAkarKuadratKaryawan]);
        // $penilaian->save();

        // Reset input
        // $this->reset(['tgl_penilaian', 'bobot']);
    }



    public function perhitungan()
    {
        $this->bbt_kehadiran = 27.40;
        $this->bbt_kinerja = 24.66;
        $this->bbt_tanggung_jawab = 24.66;
        $this->bbt_sikap = 23.29;
    }
}
