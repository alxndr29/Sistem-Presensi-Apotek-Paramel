@extends('layouts.templatepegawai')

@section('content')

@if($periode_presensi == null)
<div class="alert alert-danger alert-dismissible " role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
    <strong>Tidak Ada Periode Presensi Yang Dibuka!</strong>
</div>
@else
<div class="alert alert-success alert-dismissible text-center" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
    <strong>Segera Lakukan Absensi Sebelum Pukul {{$batas_absen}}</strong>
</div>
@endif
@if($tombol_absen == false && $periode_presensi != null && $status_presensi->status != "Hadir")
<div class="alert alert-danger alert-dismissible text-center" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
    <strong>Anda Tidak Diperbolehkan Melakukan Absensi Karena Melewati Batas Waktu</strong>
</div>
@endif

@if( $status_presensi->status == "Hadir")
<div class="alert alert-success alert-dismissible text-center" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
    <strong>Jam Kerja Hari Ini Sampai Pukul: {{$periode_presensi->jam_akhir}}</strong>
</div>
@endif

<div class="row">
    <div class="col-md-3   widget widget_tally_box mx-auto">
        <div class="x_panel fixed_height_390 ">
            <div class="x_content">
                <div class="flex">
                    <ul class="list-inline widget_profile_box">
                        <li style="display:none;">
                            <a>
                                <i class="fa fa-facebook"></i>
                            </a>
                        </li>
                        <li>
                            <img src="{{asset('gentelella-master/production/images/img.jpg')}}" alt="..." class="img-circle profile_img">
                        </li>
                        <li style="display:none;">
                            <a>
                                <i class="fa fa-twitter"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="row">
                    <h4 class="mx-auto">{{Auth::user()->name}}</h4>
                </div>
                <div class="row">

                    @if( $status_presensi->status == "Hadir")
                        @if($status_presensi->jam_absen_keluar == null)
                            <h3 class="mx-auto"> Anda Tercatat Hadir </h3>
                            <a href="{{route('pegawai.presensikeluar')}}" class="btn btn-primary mx-auto">KELUAR</a>
                        @else
                            <h3 class="mx-auto text-center">Sudah Melakukan Presensi Keluar</h3>
                        @endif
                    @else
                        @if($tombol_absen == true)
                            <a href="{{route('pegawai.presensimasuk')}}" id="btnabsen" class="btn btn-primary mx-auto">ABSEN</a>
                        @else
                            <a href="{{route('pegawai.presensimasuk')}}" id="btnabsen" class="btn btn-primary mx-auto disabled">ABSEN</a>
                        @endif
                    @endif
                </div>
                <p>
                    If you've decided to go in development mode and tweak all of this a bit, there are few things you should do.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection