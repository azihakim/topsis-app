@extends('master')
@section('title', 'User')
@section('content')
				<div class="row">
								<div class="col-2"></div>
								<div class="col-8">

												@if (session('error'))
																<div class="alert alert-danger">
																				{{ session('error') }}
																</div>
												@endif
												@if (session('success'))
																<div class="alert alert-success">
																				{{ session('success') }}
																</div>
												@endif
												<div class="card card-info">
																<div class="card-header">
																				<h3 class="card-title">Pengguna</h3>
																				<div class="card-tools">
																								<ul class="nav nav-pills ml-auto">
																												<li class="nav-item">
																																<a href="{{ route('pengguna.create') }}" class="nav-link btn btn-block btn-outline-light"><i
																																								class="fa fa-user-plus"></i>
																																				Tambah</a>
																												</li>
																								</ul>
																				</div>
																</div>
																<div class="card-body">
																				<table id="exampleY" class="table-bordered table-striped table">
																								<thead>
																												<tr>
																																<th>User</th>
																																<th>Aksi</th>
																												</tr>
																								</thead>
																								<tbody>
																												@foreach ($data as $item)
																																<tr>
																																				<td>
																																								{{ $item->name }}
																																				</td>
																																				<td style="width: 50%">
																																								<div class="row">
																																												<div class="col-6">
																																																<a href="{{ route('pengguna.edit', $item->id) }}"
																																																				class="btn btn-block btn-outline-info">Edit</a>
																																												</div>
																																												<div class="col-6">
																																																<form id="delete-form-{{ $item->id }}"
																																																				action="{{ route('pengguna.destroy', $item->id) }}" method="POST">
																																																				@csrf
																																																				@method('DELETE')
																																																				<button type="button" onclick="confirmDelete({{ $item->id }})"
																																																								class="btn btn-block btn-outline-danger">Hapus Pengguna</button>
																																																</form>
																																												</div>
																																								</div>
																																				</td>
																																</tr>
																												@endforeach
																								</tbody>
																								<tfoot>
																												<tr>
																																<th>User</th>
																																<th>Aksi</th>
																												</tr>
																								</tfoot>
																				</table>
																</div>
												</div>
								</div>
								<div class="col-2"></div>
				</div>

@endsection
@section('scripts')
				<script>
								function confirmDelete(id) {
												if (confirm("Apakah Anda yakin ingin menghapus pengguna ini?")) {
																document.getElementById('delete-form-' + id).submit();
												}
								}
				</script>
@endsection
