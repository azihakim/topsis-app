<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Http\Request;

class SubKriteriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = SubKriteria::all();
        return view('subkriteria.subkriteria', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = Kriteria::all();
        return view('subkriteria.addSubKriteria', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $subkriteria = new SubKriteria;
        $subkriteria->nama_sub_kriteria = $request->nama_sub_kriteria;
        $subkriteria->bobot = $request->bobot;
        $subkriteria->kriteria_id = $request->kriteria_id;
        $subkriteria->save();
        return redirect()->route('subkriteria.index')->with('success', 'Sub Kriteria berhasil ditambahkan');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $subkriteria = SubKriteria::find($id);
        $data = Kriteria::all();
        return view('subkriteria.editSubKriteria', compact('subkriteria', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $subkriteria = SubKriteria::find($id);
        $subkriteria->nama_sub_kriteria = $request->nama_sub_kriteria;
        $subkriteria->bobot = $request->bobot;
        $subkriteria->kriteria_id = $request->kriteria_id;
        $subkriteria->save();
        return redirect()->route('subkriteria.index')->with('success', 'Sub Kriteria berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subkriteria = SubKriteria::find($id);
        $subkriteria->delete();
        return redirect()->route('subkriteria.index')->with('success', 'Sub Kriteria berhasil dihapus');
    }
}
