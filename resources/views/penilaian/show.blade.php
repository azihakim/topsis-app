@extends('master')
@section('title', 'Penilaian')
@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Penilaian</h3>
            <a class="btn btn-primary float-right">Cetak</a>
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
                        <th>Rank</th>
                        <th>Karyawan</th>
                        <th>Tanggal Penilaian</th>
                        <th>Data</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($penilaian as $item)
                        @php
                            $data = json_decode($item->data);
                            // dd($data);
                        @endphp
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $data->nama_karyawan }}</td>
                            <td>{{ $item->tgl_penilaian }}</td>
                            <td>
                                <pre>{{ $data->data_v }}</pre>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Rank</th>
                        <th>Karyawan</th>
                        <th>Tanggal Penilaian</th>
                        <th>Data</th>
                    </tr>
                </tfoot>
            </table>

        </div>
        <!-- /.card-body -->
    </div>
@endsection
