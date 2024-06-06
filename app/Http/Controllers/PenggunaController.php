<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PenggunaController extends Controller
{
    public function index()
    {
        $data = User::all();
        return view('pengguna.pengguna', compact('data'));
    }

    public function create()
    {
        return view('pengguna.tambahPengguna');
    }

    public function store(Request $request)
    {
        $data = new User();
        $data->name = $request->name;
        $data->username = $request->username;
        $data->nip = $request->nip;
        $data->password = Hash::make($request->password);
        $data->save();

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna created successfully.');
    }

    public function edit ($id)
    {
        $data = User::find($id);
        return view('pengguna.editPengguna', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = User::find($id);
        $data->name = $request->name;
        $data->username = $request->username;
        $data->nip = $request->nip;
        $data->password = Hash::make($request->password);
        $data->save();

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna updated successfully.');
    }

    public function destroy($id)
    {
        $data = User::find($id);
        $data->delete();

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna deleted successfully.');
    }
}
