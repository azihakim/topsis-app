@extends('master')
@section('title', 'Penilaian')
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Penilaian</h3>
            <a href="{{ route('penilaian.create') }}" class="btn btn-primary float-right">Tambah Penilaian</a>
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
            <table id="example3" class="table-bordered table-striped table">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Tanggal Penilaian</th>
                        <th>Cek Penilaian</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($uniquePenilaian as $item)
                        <tr>
                            <td>
                                {{ $item->periode_penilaian }}
                            </td>
                            <td>
                                {{ $item->tgl_penilaian }}
                            </td>
                            <td>
                                <a href="{{ route('penilaian.show', $item->periode_penilaian) }}"
                                    class="btn btn-block btn-outline-info">Cek</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Periode</th>
                        <th>Tanggal Penilaian</th>
                        <th>Cek Penilaian</th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
@endsection
