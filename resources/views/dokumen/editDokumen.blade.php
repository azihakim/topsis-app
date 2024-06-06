@extends('master')
@section('title', 'Tambah Dokumen')
@section('content')
				<div class="row">
								<div class="col-12">
												<div class="card card-info">
																<div class="card-header">
																				<h3 class="card-title">Form Tambah Dokumen</h3>
																</div>
																<!-- /.card-header -->
																<!-- form start -->

																<div class="card-body">
																				<div class="row">
																								<div class="col-3">
																												<div class="form-group">
																																<label>Nama</label>
																																<input disabled name="nama" type="text" class="form-control"
																																				value="{{ $pegawai->nama }}">
																																<input type="hidden" name="pegawai_id" value={{ $pegawai->id }}>
																												</div>
																								</div>

																								<div class="col-3">
																												<div class="form-group">
																																<label>NIP</label>
																																<input disabled name="nip" type="text" class="form-control"
																																				value="{{ $pegawai->nip }}">
																												</div>
																								</div>
																								<div class="col-3">
																												<div class="form-group">
																																<label>Golongan</label>
																																<input disabled name="golongan" type="text" class="form-control"
																																				value="{{ $pegawai->golongan }}">
																												</div>
																								</div>
																								<div class="col-3">
																												<div class="form-group">
																																<label>Jabatan</label>
																																<input disabled name="jabatan" type="text" class="form-control"
																																				value="{{ $pegawai->jabatan }}">
																												</div>
																								</div>
																				</div>
																				<hr>
																				<h3>Dokumen</h3>
																				@if (session('success'))
																								<div class="alert alert-success">
																												{{ session('success') }}
																								</div>
																				@endif

																				@foreach ($dokumen as $item)
																								<div class="row">
																												<div class="col-4">
																																<div class="form-group">
																																				<label>Jenis Dokumen</label>
																																				<input disabled name="jenis" type="text" class="form-control"
																																								value="{{ $item->jenis }}">
																																</div>
																												</div>

																												<div class="col-2">
																																<div class="form-group">
																																				<label>Download Dokumen</label>
																																				<a href="{{ asset('storage/dokumen/' . $item->file) }}" download
																																								class="btn btn-outline-primary btn-block"><i class="fa fa-download"></i></a>
																																</div>
																												</div>
																												<div class="col-1">
																																<div class="form-group">
																																				<label>Hapus</label>
																																				<form id="deleteForm{{ $item->id }}" action="{{ url('dokumen/' . $item->id) }} "
																																								method="POST">
																																								@csrf
																																								<input type="hidden" name="_method" value="DELETE">
																																								<button class="btn btn-block btn-outline-danger delete-btn">Hapus</button>
																																				</form>
																																</div>
																												</div>

																												<div class="col-5">
																																<form action="{{ url('dokumen/update/' . $item->id) }}" method="POST"
																																				enctype="multipart/form-data">
																																				@csrf
																																				@method('put')
																																				<div class="form-group">
																																								<input type="hidden" name="id_pegawai" value="{{ $pegawai->id }}">
																																								<label>Upload Dokumen Baru</label>
																																								<div class="input-group">
																																												<input required name="file" type="file" class="form-control">
																																												<div class="input-group-append">
																																																<button type="submit" class="btn btn-outline-info">
																																																				<i class="fa fa-upload"></i> Perbarui Dokumen
																																																</button>
																																												</div>
																																								</div>
																																				</div>
																																</form>
																												</div>
																								</div>
																				@endforeach

																</div>

												</div>
								</div>
				</div>
@endsection

@section('scripts')
				<script>
								document.getElementById('foto').addEventListener('change', function() {
												var fileInput = this;
												var maxSize = 500 * 1024; // 500KB dalam bytes
												var files = fileInput.files;

												if (files.length > 0) {
																var fileSize = files[0].size; // Mendapatkan ukuran file pertama yang dipilih
																if (fileSize > maxSize) {
																				alert('Ukuran file melebihi batas maksimum (500KB). Silakan pilih file lain.');
																				fileInput.value = ''; // Menghapus file yang sudah dipilih
																}
												}
								});
				</script>
@endsection
