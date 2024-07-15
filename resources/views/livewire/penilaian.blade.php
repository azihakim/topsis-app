<!-- resources/views/livewire/penilaian.blade.php -->
<div>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Penilaian</h3>
            <br>
            @if ($step == 1)
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label>Periode Penilaian</label>
                            <input type="text" class="form-control" wire:model="periode" placeholder="Masukkan Judul">
                            @error('periode')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
            @endif

        </div>
        <!-- /.card-header -->
        @if ($step == 1)
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
                                    @if ($item->nama_sub_kriteria == 'Tepat Waktu')
                                        <label>{{ $item->nama_sub_kriteria }} <br> (Max 20 Hari)</label>
                                        <input required placeholder="Hari" type="number" class="form-control"
                                            wire:model="bobot.{{ $karyawan->id }}.{{ $item->id }}" min="0"
                                            max="{{ $item->bobot }}">
                                    @elseif ($item->nama_sub_kriteria == 'Total Jam Kerja')
                                        <label>{{ $item->nama_sub_kriteria }} <br> (Max 150 Jam)</label>
                                        <input required placeholder="Jam" type="number" class="form-control"
                                            wire:model="bobot.{{ $karyawan->id }}.{{ $item->id }}" min="0"
                                            max="{{ $item->bobot }}">
                                    @elseif ($item->nama_sub_kriteria == 'Izin Kerja')
                                        <label>{{ $item->nama_sub_kriteria }} <br> (Max 20 Hari)</label>
                                        <input required placeholder="Hari" type="number" class="form-control"
                                            wire:model="bobot.{{ $karyawan->id }}.{{ $item->id }}" min="0"
                                            max="{{ $item->bobot }}">
                                    @else
                                        <label>{{ $item->nama_sub_kriteria }} <br> (Max {{ $item->bobot }})</label>
                                        <input type="number" class="form-control"
                                            wire:model="bobot.{{ $karyawan->id }}.{{ $item->id }}" min="0"
                                            max="{{ $item->bobot }}">
                                    @endif
                                    <div>
                                        @error('bobot.' . $karyawan->id . '.' . $item->id)
                                            <span class="text-danger">{{ $message }}</span>
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
                <button type="submit" wire:click="next" class="btn btn-primary">
                    next
                </button>
                <button type="submit" wire:click="simpan" class="btn btn-primary">
                    simpan
                </button>
            </div>
        @elseif($step == 2)
            <div class="card-body">
                <h3>Matriks Ternormalisasi (R)</h3>
                <table class="table-bordered table-striped table">
                    <thead>
                        <tr>
                            <th>Nama Karyawan</th>
                            @foreach ($data_r[0]['bobot'] as $kriteria => $details)
                                <th>{{ $kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_r as $penilaian)
                            <tr>
                                <td>{{ $penilaian['nama_karyawan'] }}</td>
                                @foreach ($penilaian['bobot'] as $details)
                                    <td>{{ $details['normalized_total'] }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>


                <br>

                <h3>Matriks Ternormalisasi Terbobot (Y)</h3>
                <table class="table-bordered table-striped table">
                    <thead>
                        <tr>
                            <th>Nama Karyawan</th>
                            @foreach ($data_y[0]['bobot'] as $kriteria => $details)
                                <th>{{ $kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data_y as $penilaian)
                            <tr>
                                <td>{{ $penilaian['nama_karyawan'] }}</td>
                                @foreach ($penilaian['bobot'] as $details)
                                    <td>{{ $details['normalized_total'] }}</td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <br>

                <h3>A+</h3>
                <table class="table-bordered table-striped table">
                    <thead>
                        <tr>
                            @foreach ($data_ap as $kriteria => $details)
                                <th>{{ $kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach ($data_ap as $kriteria => $normalized_total)
                                <td>{{ $normalized_total }}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>


                <br>

                <h3>A-</h3>
                <table class="table-bordered table-striped table">
                    <thead>
                        <tr>
                            @foreach ($data_am as $kriteria => $details)
                                <th>{{ $kriteria }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach ($data_am as $kriteria => $normalized_total)
                                <td>{{ $normalized_total }}</td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>





                <button type="submit" wire:click="simpan" class="btn btn-primary">
                    simpan
                </button>
                <button type="submit" wire:click="back" class="btn btn-danger">
                    Kembali
                </button>
                <button type="submit" wire:click="calculateAmData" class="btn btn-danger">
                    calculateAmData
                </button>
            </div>
        @endif



        <!-- /.card-body -->
    </div>
</div>
