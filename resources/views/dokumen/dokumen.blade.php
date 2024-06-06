@extends('master')
@section('title', 'Dokumen')
@section('content')
				<div class="card">
								<div class="card-header">
												<h3 class="card-title">Dokumen Pegawai</h3>
								</div>
								<!-- /.card-header -->
								<div class="card-body">
												<table id="example1" class="table-bordered table-striped table">
																<thead>
																				<tr>
																								<th>Nama</th>
																								<th>NIP</th>
																								<th>Golongan</th>
																								<th>Jabatan</th>
																								<th>Status</th>
																								<th>Aksi</th>
																				</tr>
																</thead>
																<tbody>
																				@foreach ($pegawai as $item)
																								<tr>
																												<td>{{ $item->nama }}</td>
																												<td>{{ $item->nip }}</td>
																												<td>{{ $item->golongan }}</td>
																												<td>{{ $item->jabatan }}</td>
																												<td>
																																<div class="row">
																																				<div class= "col-6">
																																								<a class="btn btn-block btn-outline-info"
																																												href="{{ url('dokumen/create/' . $item->id) }}">Tambah</a>
																																				</div>
																																				<div class= "col-6">
																																								<a class="btn btn-block btn-outline-success"
																																												href="{{ url('dokumen/' . $item->id . '/edit') }}">Dokumen</a>
																																				</div>
																																</div>
																												</td>
																								</tr>
																				@endforeach
																</tbody>
																<tfoot>
																				<tr>
																								<th>Nama</th>
																								<th>NIP</th>
																								<th>Golongan</th>
																								<th>Jabatan</th>
																								<th>Status</th>
																								<th>Aksi</th>
																				</tr>
																</tfoot>
												</table>
								</div>
								<!-- /.card-body -->
				</div>
@endsection

@section('scripts')
				<script>
								document.getElementById('linkToNextPage').addEventListener('click', function(event) {
												// Mencegah perilaku bawaan dari tautan
												event.preventDefault();

												// Mendapatkan nilai dari input namaBiro
												var namaBiro = encodeURIComponent(document.querySelector('input[name="namaBiro"]').value);

												// Membuat URL dengan parameter query namaBiro
												var newUrl = "/laporan?namaBiro=" + namaBiro;

												// Pindah ke halaman dengan URL baru
												window.location.href = newUrl;
								});
				</script>

@endsection
