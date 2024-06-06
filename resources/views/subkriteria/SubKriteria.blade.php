@extends('master')
@section('title', 'Kriteria')
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Sub Kriteria</h3>
            <a href="{{ url('subkriteria/create') }}" class="btn btn-primary float-right">Tambah Sub Kriteria</a>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif
            <table id="example1" class="table-bordered table-striped table">
                <thead>
                    <tr>
                        <th>Kriteria</th>
                        <th>Sub Kriteria</th>
                        <th>Bobot</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $item->kriteria->nama_kriteria }}</td>
                            <td>{{ $item->nama_sub_kriteria }}</td>
                            <td>{{ $item->bobot }}</td>
                            <td>
                                <div class="row">
                                    <div class= "col-6">
                                        <a class="btn btn-block btn-outline-warning"
                                            href="{{ url('subkriteria/' . $item->id . '/edit') }}">Edit</a>
                                    </div>
                                    <div class= "col-6">
                                        <form id="deleteForm{{ $item->id }}"
                                            action="{{ url('subkriteria/' . $item->id) }} " method="POST">
                                            @csrf
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="btn btn-block btn-outline-danger delete-btn">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Kriteria</th>
                        <th>Sub Kriteria</th>
                        <th>Bobot</th>
                        <th>Aksi</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
@endsection
