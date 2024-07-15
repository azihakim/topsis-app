<?php

namespace App\Livewire;

use App\Models\Karyawan;
use App\Models\Kriteria;
use App\Models\PenilaianDb;
use App\Models\SubKriteria;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;
use Livewire\Component;
use Livewire\Features\SupportConsoleCommands\Commands\Upgrade\ThirdPartyUpgradeNotice;

class Penilaian extends Component
{
    public $karyawans;
    public $id_karyawan = [];
    public $nama_karyawan = [];
    public $divisi_karyawan = [];
    public $sub_kriteria = [];
    public $tgl_penilaian = 'p';
    public $bobot = [];
    public $data_penilaian;
    public $periode;
    // public $ket_kehadiran = 'Benefit';
    // public $ket_kinerja = 'Benefit';
    // public $ket_tanggung_jawab = 'Benefit';
    // public $ket_sikap = 'Benefit';

    public $newBobot = [];
    public $step = 1;
    public $hasil_akhir = [];
    public $data_r = [];
    public $data_y = [];
    public $data_ap = [];
    // public $apData = [];
    public $data_am = [];
    public $data_dp = [];
    public $data_dm = [];
    public $data_v = [];
    public $kriteria_totals = [];



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
        return view('livewire.penilaian', [
            'data_y' => $this->data_y,
        ]);
    }


    public function validateForm()
    {
        $isValid = true;

        foreach ($this->karyawans as $karyawan) {
            foreach ($this->sub_kriteria as $subKriteria) {
                $karyawanId = $karyawan->id;
                $subKriteriaId = $subKriteria->id;
                $namaSubKriteria = $subKriteria->nama_sub_kriteria;
                // Check if the specific sub kriteria needs validation

                // Check if the required field is empty or null
                if (!isset($this->bobot[$karyawanId][$subKriteriaId]) || $this->bobot[$karyawanId][$subKriteriaId] === null || $this->bobot[$karyawanId][$subKriteriaId] === 0) {
                    $this->addError('bobot.' . $karyawanId . '.' . $subKriteriaId, $namaSubKriteria . ' harus diisi.');
                    $isValid = false; // Set flag to false if there are validation errors
                } elseif ($this->bobot[$karyawanId][$subKriteriaId] < 0) {
                    $this->addError('bobot.' . $karyawanId . '.' . $subKriteriaId, $namaSubKriteria . ' tidak boleh bernilai negatif.');
                    $isValid = false; // Set flag to false if there are validation errors
                } else {
                    $isValid = true;
                }
            }
        }
        foreach ($this->karyawans as $karyawan) {
            foreach ($this->sub_kriteria as $item) {
                // Lakukan perhitungan seperti yang ada di calculateMultiplication
                // Ambil nilai dari input
                if (isset($this->bobot[$karyawan->id][$item->id])) {
                    $nilai = $this->bobot[$karyawan->id][$item->id];
                } else {
                    $nilai = 0;
                }

                // Inisialisasi $hasilPerkalian dengan nilai default
                $hasilPerkalian = 0;

                // Tentukan faktor perkalian berdasarkan nama sub kriteria
                if ($item->nama_sub_kriteria === 'Tepat Waktu') {
                    if ($nilai > 20) {
                        $this->addError('bobot.' . $karyawan->id . '.' . $item->id, 'Penilaian harus tidak melebihi ' . $item->bobot . '.');
                        $isValid = false;
                    } else {
                        $hasilPerkalian = $nilai;
                    }
                } elseif ($item->nama_sub_kriteria === 'Total Jam Kerja') {
                    if ($nilai > 150) {
                        $this->addError('bobot.' . $karyawan->id . '.' . $item->id, 'Penilaian harus tidak melebihi ' . $item->bobot . '.');
                        $isValid = false;
                    } else {
                        $hasilPerkalian = $nilai;
                    }
                } elseif ($item->nama_sub_kriteria === 'Izin Kerja') {
                    if ($nilai > 20) {
                        $this->addError('bobot.' . $karyawan->id . '.' . $item->id, 'Penilaian harus tidak melebihi ' . $item->bobot . '.');
                        $isValid = false;
                    } else {
                        $hasilPerkalian = $nilai;
                    }
                } else {
                    if ($nilai > $item->bobot) {
                        $this->addError('bobot.' . $karyawan->id . '.' . $item->id, 'Penilaian harus tidak melebihi ' . $item->bobot . '.');
                        $isValid = false;
                    } else {
                        $hasilPerkalian = $nilai;
                    }
                }

                // Simpan hasil perkalian (misalnya ke dalam array atau model sesuai kebutuhan)
                $this->newBobot[$karyawan->id][$item->id] = $hasilPerkalian;
            }
        }

        return $isValid;
    }

    public function next()
    {
        // $this->validateForm();
        // if (!$this->validateForm()) {
        //     // Jika validasi gagal, kembalikan atau lakukan tindakan yang sesuai
        //     return redirect()->back()->with('error', 'Ada kesalahan validasi. Silakan lengkapi semua input yang diperlukan.');
        // }
        $this->simpan();
        $this->step = 2;
    }

    public function simpan()
    {
        if (!$this->validateForm()) {
            return redirect()->back()->with('error', 'Ada kesalahan validasi. Silakan lengkapi semua input yang diperlukan.');
        }

        $this->calculateBobot();
        $penilaianData = $this->preparePenilaianData();
        $normalizedData = $this->normalizeData($penilaianData);
        $yData = $this->dataY();
        $apData = $this->calculateApData($yData);
        $amData = $this->calculateAmData($yData);
        // $dpData = $this->calculateDpData($normalizedData, $apData);
        // $dmData = $this->calculateDmData($normalizedData, $amData);
        // $finalData = $this->calculateFinalData($dpData, $dmData);

        $this->dataY();

        // dd('finalData', $finalData);
    }
    public function dataY()
    {
        $data_y = $this->data_r;
        foreach ($data_y as &$item) {
            foreach ($item['bobot'] as $kriteria => &$nilai) {
                $nilai['normalized_total'] *= $nilai['bobot_kriteria'];
            }
        }
        $this->data_y = $data_y;
        // dd('data_y', $this->data_y);
        return $this->data_y;
    }

    protected function calculateBobot()
    {
        foreach ($this->karyawans as $karyawan) {
            foreach ($this->sub_kriteria as $item) {
                $nilai = $this->bobot[$karyawan->id][$item->id] ?? 0;
                $hasilPerkalian = $this->calculateMultiplication($item, $nilai, $karyawan->id);
                $this->bobot[$karyawan->id][$item->id] = $hasilPerkalian;
            }
        }
    }

    protected function calculateMultiplication($item, $nilai, $karyawanId)
    {
        $hasilPerkalian = 0;

        switch ($item->nama_sub_kriteria) {
            case 'Tepat Waktu':
                if ($nilai > 20) {
                    $this->addError('bobot.' . $karyawanId . '.' . $item->id, 'Penilaian harus tidak melebihi ' . $item->bobot . '.');
                } else {
                    $hasilPerkalian = intval(($nilai * 0.015) * 100);
                    $this->resetErrorBag('bobot.' . $karyawanId . '.' . $item->id);
                }
                break;

            case 'Total Jam Kerja':
                if ($nilai > 150) {
                    $this->addError('bobot.' . $karyawanId . '.' . $item->id, 'Penilaian harus tidak melebihi ' . $item->bobot . '.');
                } else {
                    $hasilPerkalian = intval(($nilai * 0.00267) * 100);
                    $this->resetErrorBag('bobot.' . $karyawanId . '.' . $item->id);
                }
                break;

            case 'Izin Kerja':
                if ($nilai > 20) {
                    $this->addError('bobot.' . $karyawanId . '.' . $item->id, 'Penilaian harus tidak melebihi ' . $item->bobot . '.');
                } else {
                    $hasilPerkalian = intval(((20 - $nilai) * 0.015) * 100);
                    $this->resetErrorBag('bobot.' . $karyawanId . '.' . $item->id);
                }
                break;

            default:
                if ($nilai > $item->bobot) {
                    $this->addError('bobot.' . $karyawanId . '.' . $item->id, 'Penilaian harus tidak melebihi ' . $item->bobot . '.');
                } else {
                    $hasilPerkalian = intval($nilai * 1);
                    $this->resetErrorBag('bobot.' . $karyawanId . '.' . $item->id);
                }
                break;
        }

        return $hasilPerkalian;
    }

    protected function preparePenilaianData()
    {
        $semuaPenilaian = [];
        $kriteria_totals = [];

        foreach ($this->karyawans as $karyawan) {
            $penilaian = new PenilaianDb();
            $penilaian->karyawan_id = $karyawan->id;
            $penilaian->tgl_penilaian = $this->tgl_penilaian;
            $bobotArray = $this->prepareBobotArray($karyawan);

            foreach ($bobotArray as $kriteria => $subKriteria) {
                $total = array_sum(array_filter($subKriteria, fn ($key) => $key !== 'bobot_kriteria', ARRAY_FILTER_USE_KEY));
                $bobotArray[$kriteria]['total'] = $total;

                if (!isset($kriteria_totals[$kriteria])) {
                    $kriteria_totals[$kriteria] = 0;
                }
                $kriteria_totals[$kriteria] += pow($total, 2);
            }

            $penilaian->data = json_encode(['bobot' => $bobotArray]);
            array_push($semuaPenilaian, $penilaian);
        }

        return compact('semuaPenilaian', 'kriteria_totals');
    }

    protected function prepareBobotArray($karyawan)
    {
        $bobotArray = [];

        foreach ($this->bobot[$karyawan->id] as $subKriteriaId => $bobot) {
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

        return $bobotArray;
    }

    protected function normalizeData($data)
    {
        $semuaPenilaian = $data['semuaPenilaian'];
        $kriteria_totals = $data['kriteria_totals'];
        $kriteria_sqrt = [];

        foreach ($kriteria_totals as $kriteria => $total) {
            $kriteria_sqrt[$kriteria] = sqrt($total);
        }

        $normalizedData = [];

        foreach ($semuaPenilaian as $penilaian) {
            $penilaianData = json_decode($penilaian['data'], true);
            $normalizedBobots = [];

            foreach ($penilaianData['bobot'] as $kriteria => $subKriteria) {
                $total = isset($subKriteria['total']) ? $subKriteria['total'] : 0;
                $normalizedTotal = $total / $kriteria_sqrt[$kriteria];

                $normalizedBobots[$kriteria] = [
                    'bobot_kriteria' => isset($subKriteria['bobot_kriteria']) ? $subKriteria['bobot_kriteria'] : 0,
                    'total' => $total,
                    'normalized_total' => $normalizedTotal,
                ];
            }

            $normalizedPenilaian = [
                'karyawan_id' => $penilaian['karyawan_id'],
                'tgl_penilaian' => $penilaian['tgl_penilaian'],
                'bobot' => $normalizedBobots,
            ];

            $normalizedData[] = $normalizedPenilaian;
        }

        $this->data_r = $normalizedData;
        // dd('sss', $this->data_r);
        // $this->data_y = $normalizedData;
        return $this->data_r;
    }

    protected function calculateApData($yData)
    {
        // Initialize an empty array to store the results
        $data_ap = [];

        // Iterate over each employee's data
        foreach ($yData as $penilaian) {
            // Iterate over each criterion for the current employee
            foreach ($penilaian['bobot'] as $kriteria => $details) {
                // Fetch the criterion details from the database
                $data_kriteria = Kriteria::where('nama_kriteria', $kriteria)->first();

                // Check if the criterion is of type 'Benefit'
                if ($data_kriteria && $data_kriteria->keterangan == 'Benefit') {
                    // If this criterion does not exist in $data_ap, initialize it
                    if (!isset($data_ap[$kriteria])) {
                        $data_ap[$kriteria] = $details['normalized_total'];
                    } else {
                        // Calculate the minimum normalized total for 'Benefit' criteria
                        $data_ap[$kriteria] = max($data_ap[$kriteria], $details['normalized_total']);
                    }
                } elseif ($data_kriteria && $data_kriteria->keterangan == 'Cost') {
                    // If this criterion does not exist in $data_ap, initialize it
                    if (!isset($data_ap[$kriteria])) {
                        $data_ap[$kriteria] = $details['normalized_total'];
                    } else {
                        // Calculate the maximum normalized total for 'Cost' criteria
                        $data_ap[$kriteria] = min($data_ap[$kriteria], $details['normalized_total']);
                    }
                }
            }
        }

        $this->data_ap = $data_ap;
        // dd('ap', $this->data_ap);

        // Return the calculated data
        return $this->data_ap;
    }


    protected function calculateAmData($yData)
    {
        // Initialize an empty array to store the results
        $data_am = [];

        // Iterate over each employee's data
        foreach ($yData as $penilaian) {
            // Iterate over each criterion for the current employee
            foreach ($penilaian['bobot'] as $kriteria => $details) {
                // Fetch the criterion details from the database
                $data_kriteria = Kriteria::where('nama_kriteria', $kriteria)->first();

                // Check if the criterion is of type 'Benefit'
                if ($data_kriteria && $data_kriteria->keterangan == 'Benefit') {
                    // If this criterion does not exist in $data_am, initialize it
                    if (!isset($data_am[$kriteria])) {
                        $data_am[$kriteria] = $details['normalized_total'];
                    } else {
                        // Calculate the minimum normalized total for 'Benefit' criteria
                        $data_am[$kriteria] = min($data_am[$kriteria], $details['normalized_total']);
                    }
                } elseif ($data_kriteria && $data_kriteria->keterangan == 'Cost') {
                    // If this criterion does not exist in $data_am, initialize it
                    if (!isset($data_am[$kriteria])) {
                        $data_am[$kriteria] = $details['normalized_total'];
                    } else {
                        // Calculate the maximum normalized total for 'Cost' criteria
                        $data_am[$kriteria] = max($data_am[$kriteria], $details['normalized_total']);
                    }
                }
            }
        }
        // dd($data_am);
        $this->data_am = $data_am;
        // Return the calculated data
        return $this->data_am;
    }



    protected function calculateDpData($normalizedData, $apData)
    {
        // Mengambil semua karyawan
        foreach ($this->karyawans as $karyawan) {
            $nama_karyawan[] = $karyawan->nama; // Menambahkan nama karyawan ke dalam array
        }

        $data_dp = $this->data_y;

        foreach ($this->karyawans as $karyawan['id'] => $karyawan) {
            foreach ($data_dp as &$item) {
                $total_kuadrat = 0;
                foreach ($item['bobot'] as $kriteria => $detail_kriteria) {
                    $total_kuadrat += pow($apData[$kriteria] - $detail_kriteria['total'], 2);
                }
                $item['total_kuadrat'] = sqrt($total_kuadrat);
            }
        }

        $this->data_dp = $data_dp;
        dd('dp', $this->data_dp);
        return $this->data_dp;
    }

    protected function calculateDmData($normalizedData, $amData)
    {
        // Mengambil semua karyawan
        foreach ($this->karyawans as $karyawan) {
            $nama_karyawan[] = $karyawan->nama; // Menambahkan nama karyawan ke dalam array
        }

        $data_dm = $this->data_y;


        foreach ($this->karyawans as $karyawan['id'] => $karyawan) {
            foreach ($data_dm as &$item) {
                $total_kuadrat = 0;
                foreach ($item['bobot'] as $kriteria => $detail_kriteria) {
                    $total_kuadrat += pow($amData[$kriteria] - $detail_kriteria['total'], 2);
                }
                $item['total_kuadrat'] = sqrt($total_kuadrat);
            }
        }

        $this->data_dm = $data_dm;

        return $this->data_dm;
    }

    protected function calculateFinalData($dpData, $dmData)
    {
        // $this->data_v = [];
        dd($dpData);
        $total_kuadrat_dp = array_column($dpData, 'total_kuadrat');
        $total_kuadrat_dm = array_column($dmData, 'total_kuadrat');

        foreach ($this->karyawans as $index => &$karyawan) {
            $total_dp = $total_kuadrat_dp[$index];
            $total_dm = $total_kuadrat_dm[$index];
            $this->data_v[] = [
                'index' => $index,
                'value' => ($total_dm + $total_dp != 0) ? $total_dm / ($total_dm + $total_dp) : 0
            ];
        }

        usort($this->data_v, function ($a, $b) {
            return $b['value'] <=> $a['value'];
        });

        return $this->data_v;
    }











    public function back()
    {
        foreach ($this->karyawans as $karyawan) {
            foreach ($this->sub_kriteria as $subKriteria) {
                $karyawanId = $karyawan->id;
                $subKriteriaId = $subKriteria->id;
                $this->bobot[$karyawanId][$subKriteriaId];
            }
        }
        $this->step = 1;
    }

    public function store()
    {
        $semuaPenilaian = [];
        foreach ($this->hasil_akhir as $hasil) {
            // Cari ID karyawan berdasarkan nama karyawan
            $nama_karyawan = $hasil['nama_karyawan'];
            $karyawan = Karyawan::where('nama', $nama_karyawan)->first();

            // Buat entri baru dalam PenilaianDb jika karyawan ditemukan

            $penilaian = new PenilaianDb();
            $penilaian->periode_penilaian = $this->periode; // Masukkan ID karyawan
            $penilaian->nama_karyawan = $karyawan->nama; // Masukkan ID karyawan
            $penilaian->tgl_penilaian = Carbon::now()->format('Y-m-d');
            $penilaian->data = json_encode($hasil);
            $penilaian->save();

            $semuaPenilaian[] = $penilaian;
        }
        // $this->tgl_penilaian = $penilaian->tgl_penilaian;
        // $this->cetakLaporan($semuaPenilaian);
        return redirect()->route('penilaian.index');
    }



    public function cetakLaporan($semuaPenilaian)
    {
        // Ensure UTF-8 encoding for all strings
        array_walk_recursive($semuaPenilaian, function (&$item, $key) {
            if (is_string($item)) {
                $item = mb_convert_encoding($item, 'UTF-8');
            }
        });

        $penilaian = $semuaPenilaian;
        $content = Pdf::loadView('penilaian.cetakLaporan', compact('penilaian'));

        // Set file name for the downloaded PDF
        $filename = 'Hasil Penilaian ' . $this->tgl_penilaian . '.pdf';

        return response()->streamDownload(
            function () use ($content) {
                echo $content->stream();
            },
            $filename
        );
    }
}
