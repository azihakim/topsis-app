<?php

namespace App\Http\Controllers;

use App\Models\PenilaianDb;
use Illuminate\Http\Request;

class PenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Menyaring data penilaian berdasarkan tanggal
        $penilaian = PenilaianDb::orderBy('tgl_penilaian')->get();

        // Mengelompokkan data berdasarkan tanggal penilaian
        $groupedPenilaian = $penilaian->groupBy('tgl_penilaian');

        // Mengambil satu entri pertama dari setiap grup
        $uniquePenilaian = $groupedPenilaian->map(function ($group) {
            return $group->first();
        });

        // Mengembalikan view dengan data yang telah disaring
        return view('penilaian.index', compact('uniquePenilaian'));
    }


    public function create()
    {
        return view('penilaian.penilaian');
    }

    public function show($tgl_penilaian)
    {
        $penilaian = PenilaianDb::where('tgl_penilaian', $tgl_penilaian)->get();
        return view('penilaian.show', compact('penilaian'));
    }
}
