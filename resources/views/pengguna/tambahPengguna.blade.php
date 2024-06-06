@extends('master')
@section('title', 'Tambah Pengguna')
@section('content')
				<div class="row">
								<div class="col-4"></div>
								<div class="col-4">
												<div class="card card-info">
																@if (session('error'))
																				<div class="alert alert-danger">
																								{{ session('error') }}
																				</div>
																@endif

																<div class="card-header">
																				<h3 class="card-title">Tambah Pengguna</h3>
																</div>
																<div class="card-body">
																				<form action="{{ route('pengguna.store') }}" method="POST" enctype="multipart/form-data">
																								@csrf

																								<div class="form-group">
																												<label for="name">Nama</label>
																												<input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
																																value="{{ old('name') }}" required>
																												@error('name')
																																<div class="invalid-feedback">{{ $message }}</div>
																												@enderror
																								</div>

																								<div class="form-group">
																												<label for="biro">NIP</label>
																												<input type="text" name="nip" class="form-control @error('name') is-invalid @enderror"
																																required>
																								</div>

																								<div class="form-group">
																												<label for="username">Username</label>
																												<input type="text" name="username"
																																class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}"
																																required>
																												@error('username')
																																<div class="invalid-feedback">{{ $message }}</div>
																												@enderror
																								</div>

																								<div class="form-group">
																												<label for="password">Password</label>
																												<input type="text" name="password" class="form-control">
																								</div>

																								<button type="submit" class="btn btn-block btn-info float-right">Simpan</button>
																				</form>

																</div>
												</div>
								</div>
								<div class="col-4"></div>
				</div>

@endsection
