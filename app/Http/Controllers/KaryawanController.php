<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Karyawan;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
        // Validasi input termasuk pengecekan unik untuk username
        $request->validate([
            'nama' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8'
        ]);

        // Simpan data karyawan
        $karyawan = new Karyawan;
        $karyawan->nama = $request->nama;
        $karyawan->divisi = $request->divisi;
        $karyawan->save();

        // Simpan data user
        $user = new User();
        $user->karyawan_id = $karyawan->id;
        $user->name = $request->nama;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->role = 'Karyawan';
        $user->save();
        // dd($karyawan, $user);
        return redirect()->route('karyawan.index')
            ->with('success', 'Karyawan Berhasil diSimpan.');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $karyawan = Karyawan::find($id);
        $user_id = $karyawan->id;
        $user = user::where('karyawan_id', $user_id)->first();
        // dd($user);
        return view('karyawan.editKaryawan', compact('karyawan', 'user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        $karyawan = Karyawan::findOrFail($id);
        $karyawan->nama = $request->nama;
        $karyawan->divisi = $request->divisi;

        $cekUsername = User::where('karyawan_id', $karyawan->id)->first();
        if ($request->username != $cekUsername->username) {
            $user = user::where('karyawan_id', $id)->first();
            $request->validate([
                'username' => 'required|string|max:255|unique:users,username',
            ]);
        };
        $karyawan->save();



        $user = user::where('karyawan_id', $id)->first();
        $user->name = $request->nama;
        $user->karyawan_id = $karyawan->id;
        $user->username = $request->username;
        if ($request->password != null) {
            $user->password = Hash::make($request->password);
        }
        $user->role = 'Karyawan';
        $user->save();

        return redirect()->route('karyawan.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $karyawan = Karyawan::find($id);
        $user = user::where('karyawan_id', $id)->first();
        $user->delete();
        $karyawan->delete();

        return redirect()->route('karyawan.index')->with('success', 'Karyawan Berhasil diHapus.');
    }
}
