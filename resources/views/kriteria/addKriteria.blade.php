@extends('master')
@section('title', 'Tambah Kriteria')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Form Tambah Kriteria</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{ route('kriteria.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Kriteria</label>
                                    <input name="nama_kriteria" type="text" class="form-control"
                                        placeholder="Masukkan Kriteria">
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label>Bobot</label>
                                    <input name="bobot" type="number" class="form-control" placeholder="Masukkan Bobot">
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
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
