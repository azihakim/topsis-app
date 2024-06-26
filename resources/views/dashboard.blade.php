@extends('master')
@section('title', 'Dashboard')
@section('content')
    <div class="row">
        {{-- <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-info">{{ $count_jenis }}</span>
                <div class="info-box-content">
                    <span class="info-box-text">
                        <h4>Jenis Barang</h4>
                    </span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box">
                <span class="info-box-icon bg-warning">{{ $count_pemesanan }}</span>
                <div class="info-box-content">
                    <span class="info-box-text">
                        <h4>Pesanan</h4>
                    </span>
                </div>
            </div>
        </div> --}}
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row" style="display: flex; justify-content: center; align-items: center;">
                {{-- <img src="{{ asset('vendors/img/gedung.jpg') }}" style="width: 100%; height: ; object-fit: cover"
																				alt="img gedung setda"> --}}
                <img src="{{ asset('vendors/img/KOP SURAT INTIMEDIA.jpg') }}" style="width: 100%">
            </div>
            <br>
            <div class="row">
                <div class="col-12" style="display: flex; center-content: center; align-items: center;">
                    <p>PT Intimedia Sarana Mandiri adalah Perusahaan yang bergerak di bidang konstruksi, pembangunan, dan
                        pengelolaan proyek-proyek konstruksi yang didirikan pada tahun 2021. PT Intimedia Sarana Mandiri
                        berlokasi
                        di Jl. RE Martadinata No. 894, Kelurahan Kalidoni, Kecamatan Ilir Timur II, Palembang. Tugas dari PT
                        Intimedia Sarana Mandiri bertanggung jawab untuk merencanakan, mengelola, dan melaksanakan
                        proyek-proyek
                        konstruksi, baik untuk infrastruktur maupun bangunan komersial.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div style="display: flex; justify-content: center; align-items: center;">
                        <h4 style="margin-top: 20px"><strong>Visi</strong></h4>
                    </div>
                    <br>
                    <div style="text-align: justify;">
                        <p>Menjadi perusahaan konstruksi terpercaya dan ternama di kota palembang dan menciptakan lingkungan
                            kerja yang nyaman dan aman bagi karyawan.
                        </p>
                    </div>
                </div>
                <div class="col-6">
                    <div style="display: flex; justify-content: center; align-items: center;">
                        <h4 style="margin-top: 20px"><strong>Misi</strong></h4>
                    </div>
                    <br>
                    <div style="text-align: justify">
                        <ul>
                            <li>
                                Menjadi mitra yang dapat diandalkan dalam membangun infrastruktur yang
                                berkualitas,berkelanjutan, dan bermanfaat bagi masyarakat.
                            </li>
                            <li>
                                Membangun hubungan kerja yang harmonis dengan klien dan pihak-pihak terkait lainnya.
                            </li>
                            <li>Mengembangkan sumber daya manusia yang berkualitas dan kompeten.
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var deleteButtons = document.querySelectorAll('.delete-btn');

            deleteButtons.forEach(function(button) {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    var id = this.getAttribute('data-id');
                    var confirmDelete = confirm('Apakah Anda yakin ingin menghapus pegawai ini?');

                    if (confirmDelete) {
                        document.getElementById('deleteForm' + id).submit();
                    }
                });
            });
        });
    </script>
@endsection
