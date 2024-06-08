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

    public $ket_kehadiran = 'Benefit';
    public $ket_kinerja = 'Benefit';
    public $ket_tanggung_jawab = 'Benefit';
    public $ket_sikap = 'Benefit';


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
        // dd($dataSemua);
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
        // dd($sqrtKehadiran, $sqrtKinerja, $sqrtTanggungJawab, $sqrtSikap);

        $data_r = $semuaData;
        foreach ($data_r as &$item) {
            if (isset($item['bobot']['Kehadiran']['total'])) {
                $item['bobot']['Kehadiran']['total'] = $item['bobot']['Kehadiran']['total'] / $sqrtKehadiran;
            }
            if (isset($item['bobot']['Kinerja']['total'])) {
                $item['bobot']['Kinerja']['total'] = $item['bobot']['Kinerja']['total'] / $sqrtKinerja;
            }
            if (isset($item['bobot']['Tanggung Jawab']['total'])) {
                $item['bobot']['Tanggung Jawab']['total'] = $item['bobot']['Tanggung Jawab']['total'] / $sqrtTanggungJawab;
            }
            if (isset($item['bobot']['Sikap']['total'])) {
                $item['bobot']['Sikap']['total'] = $item['bobot']['Sikap']['total'] / $sqrtSikap;
            }
        }
        // dd($data_r);

        $data_y = $data_r;
        foreach ($data_y as &$item) {
            $item['bobot']['Kehadiran']['total'] = $item['bobot']['Kehadiran']['total'] * $this->bbt_kehadiran;
            $item['bobot']['Kinerja']['total'] = $item['bobot']['Kinerja']['total'] * $this->bbt_kinerja;
            $item['bobot']['Tanggung Jawab']['total'] = $item['bobot']['Tanggung Jawab']['total'] * $this->bbt_tanggung_jawab;
            $item['bobot']['Sikap']['total'] = $item['bobot']['Sikap']['total'] * $this->bbt_sikap;
        }
        // dd($data_y);

        $data_ap = $data_y;
        $kehadiran_totals = [];
        $kinerja_totals = [];
        $tanggung_jawab_totals = [];
        $sikap_totals = [];

        foreach ($data_ap as &$item) {
            // Collecting total values
            $kehadiran_totals[] = $item['bobot']['Kehadiran']['total'];
            $kinerja_totals[] = $item['bobot']['Kinerja']['total'];
            $tanggung_jawab_totals[] = $item['bobot']['Tanggung Jawab']['total'];
            $sikap_totals[] = $item['bobot']['Sikap']['total'];
        }

        if ($this->ket_kehadiran == 'Benefit') {
            $kehadiran_ap = max($kehadiran_totals);
        } else {
            $kehadiran_ap = min($kehadiran_totals);
        }
        if ($this->ket_kinerja == 'Benefit') {
            $kinerja_ap = max($kinerja_totals);
        } else {
            $kinerja_ap = min($kinerja_totals);
        }
        if ($this->ket_tanggung_jawab == 'Benefit') {
            $tanggung_jawab_ap = max($tanggung_jawab_totals);
        } else {
            $tanggung_jawab_ap = min($tanggung_jawab_totals);
        }
        if ($this->ket_sikap == 'Benefit') {
            $sikap_ap = max($sikap_totals);
        } else {
            $sikap_ap = min($sikap_totals);
        }

        if ($this->ket_kehadiran == 'Cost') {
            $kehadiran_am = max($kehadiran_totals);
        } else {
            $kehadiran_am = min($kehadiran_totals);
        }
        if ($this->ket_kinerja == 'Cost') {
            $kinerja_am = max($kinerja_totals);
        } else {
            $kinerja_am = min($kinerja_totals);
        }
        if ($this->ket_tanggung_jawab == 'Cost') {
            $tanggung_jawab_am = max($tanggung_jawab_totals);
        } else {
            $tanggung_jawab_am = min($tanggung_jawab_totals);
        }
        if ($this->ket_sikap == 'Cost') {
            $sikap_am = max($sikap_totals);
        } else {
            $sikap_am = min($sikap_totals);
        }


        dd($kehadiran_ap, $kinerja_ap, $tanggung_jawab_ap, $sikap_ap, '---', $kehadiran_am, $kinerja_am, $tanggung_jawab_am, $sikap_am);
        dd($data_ap);



        // $penilaian->save();
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
