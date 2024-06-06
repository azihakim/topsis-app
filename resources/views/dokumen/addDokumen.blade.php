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
																<form action="{{ route('dokumen.store') }}" method="POST" enctype="multipart/form-data">
																				@csrf
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
																																				<input name="nip" type="hidden" class="form-control" value="{{ $pegawai->nip }}">
																																				<input name="pegawai_id" type="hidden" class="form-control"
																																								value="{{ $pegawai->id }}">
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

																								<div class="row">
																												<div class="col-3">
																																<div class="form-group">
																																				<label>Jenis Dokumen</label>
																																				<select name="jenis" class="form-control select2" style="width: 100%;">
																																								<option>Pilih Jenis Dokumen</option>
																																								<option value="SK Pangkat Terakhir">SK Pangkat Terakhir</option>
																																								<option value="SK CPNS">SK CPNS</option>
																																								<option value="SK PNS">SK PNS</option>
																																								<option value="Surat Pernyataan Melaksanakan Tugas">Surat Pernyataan Melaksanakan
																																												Tugas</option>
																																								<option value="Surat Pernyataan Menduduki Jabatan">Surat Pernyataan Menduduki
																																												Jabatan</option>
																																								<option value="Surat Pernyataan Pelantikan">Surat Pernyataan Pelantikan</option>
																																								<option value="SKP 2(Dua) Tahun terakhir">SKP 2(Dua) Tahun terakhir</option>
																																								<option value="Ijazah Terakhir">Ijazah Terakhir</option>
																																								<option value="Surat Uraian Tugas">Surat Uraian Tugas</option>
																																				</select>
																																</div>
																												</div>

																												<div class="col-3">
																																<div class="form-group">
																																				<label>File Dokumen</label>
																																				<div class="input-group">
																																								<input name="file" type="file">
																																				</div>
																																</div>
																												</div>
																								</div>

																				</div>
																				<div class="card-footer">
																								<button type="submit" class="btn btn-info">Simpan</button>
																				</div>
																</form>
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
