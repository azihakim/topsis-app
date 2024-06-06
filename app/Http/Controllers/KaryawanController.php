<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Karyawan;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class KaryawanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Karyawan::all();
        return view('karyawan.karyawan', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('karyawan.addKaryawan');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $karyawan = new Karyawan;
        $karyawan->nama = $request->nama;
        $karyawan->jabatan = $request->jabatan;
        $karyawan->save();

        return redirect()->route('karyawan.index')
            ->with('success', 'Karyawan Berhasil diSimpan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $karyawan = Karyawan::find($id);
        return view('karyawan.editKaryawan', compact('karyawan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->nama = $request->nama;
        $karyawan->jabatan = $request->jabatan;
        $karyawan->save();

        return redirect()->route('karyawan.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->delete();

        return redirect()->route('karyawan.index')->with('success', 'Karyawan Berhasil diHapus.');
    }
}
