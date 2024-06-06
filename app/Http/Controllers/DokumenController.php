<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dokumen = Dokumen::select(
            'dokumens.id',
            'dokumens.jenis',
            'dokumens.file',
            'pegawai.nama',
            'pegawai.nip',
            'pegawai.jabatan',
            'pegawai.golongan',
            'pegawai.foto'
        )
        ->join('pegawais as pegawai', 'pegawai.id', '=', 'dokumens.pegawai_id')
        ->get();
        $pegawai = Pegawai::all();
        return view('dokumen.dokumen', compact('pegawai', 'dokumen'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $pegawai = Pegawai::find($id);

        // Logika untuk menampilkan form pembuatan dokumen
        return view('dokumen.addDokumen', compact('pegawai'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $nip = $request->nip;
        $dokmen = new Dokumen();
        $dokmen->jenis = $request->jenis;
        $ext = $request->file->getClientOriginalExtension();
        $file = "Dokumen-".$dokmen->jenis. "-" . $nip .".".$ext;
        $request->file->storeAs('public/dokumen', $file);
        $dokmen->file = $file;
        $dokmen->pegawai_id = $request->pegawai_id;
        $dokmen->save();

        return redirect()->route('dokumen.index')
            ->with('success', 'Dokumen Berhasil diSimpan.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Dokumen $dokumen)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dokumen $dokumen, $id)
    {
        $dokumen = Dokumen::select(
        'dokumens.id',
        'dokumens.jenis',
        'dokumens.file',
        'pegawai.nama',
        'pegawai.nip',
        'pegawai.jabatan',
        'pegawai.golongan',
        'pegawai.foto'
        )
        ->join('pegawais as pegawai', 'pegawai.id', '=', 'dokumens.pegawai_id')
        ->where('pegawai.id', $id)
        ->get();
        $pegawai = Pegawai::find($id);
        return view('dokumen.editDokumen', compact('pegawai', 'dokumen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $dokumen = Dokumen::find($id);
        $jenis = $dokumen->jenis;
        $nip = $dokumen->pegawai->nip;
        // Jika file baru diunggah, proses penyimpanannya
        if ($request->hasFile('file')) {
            // Hapus file lama jika ada
            if ($dokumen->file) {
                Storage::delete('public/dokumen/'.$dokumen->file);
            }

            // Simpan file baru
            $ext = $request->file('file')->getClientOriginalExtension();
            $file = "Dokumen - ".$jenis . " - " . $nip .".".$ext;
            $request->file('file')->storeAs('public/dokumen', $file);
            $dokumen->file = $file;
        }
        $dokumen->save();
// dd($request->id_pegawai);
        // Redirect to the appropriate route
        return redirect()->back()
            ->with('success', 'Dokumen Berhasil di Update.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $dokumen = Dokumen::find($id);
        if ($dokumen) {
            Storage::delete('public/dokumen/'.$dokumen->file);
            $dokumen->delete();
            return redirect()->back()
                ->with('success', 'Dokumen Berhasil dihapus.');
        }
    }
}
