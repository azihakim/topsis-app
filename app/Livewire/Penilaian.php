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
    public $bobot = [];

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
        $kriteria_totals = [];
        $kriteria_sqrt = [];

        foreach ($this->karyawans as $sub) {
            $penilaian = new PenilaianDb();
            $penilaian->karyawan_id = $sub->id;
            $penilaian->tgl_penilaian = $this->tgl_penilaian;

            $bobotArray = [];

            foreach ($this->bobot[$sub->id] as $subKriteriaId => $bobot) {
                $subKriteria = SubKriteria::find($subKriteriaId);
                $namaKriteria = $subKriteria->kriteria->nama_kriteria;
                $namaSubKriteria = $subKriteria->nama_sub_kriteria;

                if (!isset($bobotArray[$namaKriteria])) {
                    $bobotArray[$namaKriteria] = [];
                }

                if (!isset($bobotArray[$namaKriteria]['bobot_kriteria'])) {
                    $bobotArray[$namaKriteria]['bobot_kriteria'] = $subKriteria->kriteria->bobot;
                }

                $bobotArray[$namaKriteria][$namaSubKriteria] = $bobot;
            }

            foreach ($bobotArray as $kriteria => $subKriteria) {
                $total = 0; // Set total awal ke 0
                foreach ($subKriteria as $namaSubKriteria => $bobot) {
                    if ($namaSubKriteria !== 'bobot_kriteria') {
                        $total += $bobot;
                    }
                }
                $bobotArray[$kriteria]['total'] = $total;

                if (!isset($kriteria_totals[$kriteria])) {
                    $kriteria_totals[$kriteria] = 0;
                }
                $kriteria_totals[$kriteria] += pow($total, 2);
            }

            $penilaian->data = json_encode(['bobot' => $bobotArray]);
            array_push($semuaPenilaian, $penilaian);
        }

        foreach ($semuaPenilaian as $penilaian) {
            $data = json_decode($penilaian->data, true);
            array_push($semuaData, $data);
        }
        // Menghitung akar kuadrat untuk setiap kriteria
        foreach ($kriteria_totals as $kriteria => $total) {
            $kriteria_sqrt[$kriteria] = sqrt($total);
        }


        // PEMBAGI
        // dd($kriteria_sqrt);

        // Hitung total kuadrat kriteria dan simpan dalam array asosiatif
        // $kriteria_totals = [];
        // foreach ($data_r as &$item) {
        //     foreach ($item['bobot'] as $kriteria => $nilai) {
        //         if (!isset($kriteria_totals[$kriteria])) {
        //             $kriteria_totals[$kriteria] = 0;
        //         }
        //         $kriteria_totals[$kriteria] += pow($nilai['total'], 2);
        //     }
        // }

        // foreach ($data_r as &$item) {
        //     foreach ($item['bobot'] as $kriteria => $nilai) {
        //         $item['bobot'][$kriteria]['total'] = $nilai['total'] / $kriteria_sqrt[$kriteria];
        //     }
        // }
        // dd($semuaData);
        $data_r = $semuaData;
        foreach ($data_r as &$item) {
            foreach ($item['bobot'] as $kriteria => $nilai) {
                $item['bobot'][$kriteria]['total'] = $nilai['total'] / $kriteria_sqrt[$kriteria];
            }
        }
        // dd($data_r);

        $data_y = $data_r;
        // dd($data_y);
        // Normalisasi bobot
        foreach ($data_y as &$item) {
            foreach ($item['bobot'] as $kriteria => &$nilai) {
                $nilai['total'] *= $nilai['bobot_kriteria'];
            }
        }

        // dd($data_y);

        $data_ap = $data_y;
        // dd($data_ap);
        foreach ($data_ap as &$item) {
            // Mengumpulkan total nilai kriteria
            foreach ($kriteria_totals as $kriteria => &$totals) {
                if (isset($item['bobot'][$kriteria]['total'])) {
                    // Pastikan $totals adalah array sebelum menambahkan nilai
                    if (!is_array($totals)) {
                        $totals = [];
                    }
                    $totals[] = $item['bobot'][$kriteria]['total'];
                }
            }
        }
        // dd($kriteria_totals);
        $data_ap = [];
        foreach ($kriteria_totals as $kriteria => &$totals) {
            if ($this->ket_kehadiran == 'Benefit') {
                $data_ap[$kriteria] = max(($totals));
            } else {
                $data_ap[$kriteria] = min(($totals));
            }
        }
        // dd($data_ap);

        $data_am = [];
        foreach ($kriteria_totals as $kriteria => &$totals) {
            if ($this->ket_kehadiran == 'Cost') {
                $data_am[$kriteria] = max(($totals));
            } else {
                $data_am[$kriteria] = min(($totals));
            }
        }
        // dd($data_am);

        // Inisialisasi array jarak$jarak = [];

        // Mengambil semua karyawan
        foreach ($this->karyawans as $karyawan) {
            $nama_karyawan[] = $karyawan->nama; // Menambahkan nama karyawan ke dalam array
        }
        // dd($data_ap);
        // dd($data_y);
        $data_dp = $data_y;

        foreach ($this->karyawans as $karyawan['id'] => $karyawan) {
            foreach ($data_dp as &$item) {
                $total_kuadrat = 0;
                foreach ($item['bobot'] as $kriteria => $detail_kriteria) {
                    $total_kuadrat += pow($data_ap[$kriteria] - $detail_kriteria['total'], 2);
                }
                $item['total_kuadrat'] = sqrt($total_kuadrat);
            }
        }
        // dd($data_dp);


        $data_dm = $data_y;

        foreach ($this->karyawans as $karyawan['id'] => $karyawan) {
            foreach ($data_dm as &$item) {
                $total_kuadrat = 0;
                foreach ($item['bobot'] as $kriteria => $detail_kriteria) {
                    $total_kuadrat += pow($data_am[$kriteria] - $detail_kriteria['total'], 2);
                }
                $item['total_kuadrat'] = sqrt($total_kuadrat);
            }
        }

        // dd($data_ap, "<br>", $data_am);
        // dd($data_dp, "<br>", $data_dm);
        // dd($data_dp);
        // $total_kuadrat = array_column($data_dp, 'total_kuadrat');
        // dd($total_kuadrat);



        $data_v = [];

        // Hitung nilai data_v untuk setiap karyawan
        $total_kuadrat_dp = array_column($data_dp, 'total_kuadrat');
        $total_kuadrat_dm = array_column($data_dm, 'total_kuadrat');

        foreach ($this->karyawans as $index => &$karyawan) {
            $total_dp = $total_kuadrat_dp[$index];
            $total_dm = $total_kuadrat_dm[$index];
            $data_v[] = [
                'index' => $index,
                'value' => $total_dm / ($total_dm + $total_dp)
            ];
        }

        // Urutkan array data_v secara descending berdasarkan nilai
        usort($data_v, function ($a, $b) {
            return $b['value'] <=> $a['value'];
        });

        // Berikan peringkat kepada setiap karyawan
        foreach ($data_v as $rank => $item) {
            $karyawan_index = $item['index'];
            $this->karyawans[$karyawan_index]['data_v'] = $item['value'];
            $this->karyawans[$karyawan_index]['rank'] = $rank + 1; // Penambahan +1 karena indeks dimulai dari 0
        }

        dd($this->karyawans);



        // $penilaian->save();
        // $this->reset(['tgl_penilaian', 'bobot']);
    }
}
