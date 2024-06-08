<!-- resources/views/livewire/penilaian.blade.php -->
<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Penilaian</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            @foreach ($karyawans as $karyawan)
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Nama</label>
                            <input disabled type="text" class="form-control"
                                wire:model="nama_karyawan.{{ $karyawan->id }}">
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Divisi</label>
                            <input disabled type="text" class="form-control"
                                wire:model="divisi_karyawan.{{ $karyawan->id }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    @foreach ($sub_kriteria as $item)
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>{{ $item->nama_sub_kriteria }}</label>
                                <input required type="number" class="form-control"
                                    wire:model="bobot.{{ $karyawan->id }}.{{ $item->id }}">
                            </div>
                        </div>
                    @endforeach
                </div>
                <hr>
            @endforeach
            <a wire:click="simpan" class="btn btn-primary">
                Simpan
            </a>
        </div>
        <!-- /.card-body -->
    </div>
</div>
