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
                                    wire:model="bobot.{{ $karyawan->id }}.{{ $item->id }}" min="0"
                                    max="{{ $item->bobot }}"
                                    wire:change="calculateMultiplication('{{ $karyawan->id }}', '{{ $item->id }}', '{{ $item->nama_sub_kriteria }}', {{ $item->bobot }})">
                                <div>
                                    @error('bobot.' . $karyawan->id . '.' . $item->id)
                                        <span class="text-danger">{{ $message }}</span>
                                    @else
                                        <span class="text-danger invisible">Error message</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>
                <hr>
            @endforeach
            {{-- <a wire:click="simpan" class="btn btn-primary">
                Simpan
            </a> --}}
            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif
            <button type="submit" wire:click="simpan" class="btn btn-primary">
                Simpan
            </button>
        </div>
        <!-- /.card-body -->
    </div>
</div>
