@extends('layouts.templatepegawai')

@section('content')
<div class="card">
    <div class="card-header">
        Laporan Presensi Pegawai
    </div>
    <div class="card-body">
        @if(Session::has('sukses'))
        <div class="alert alert-success" role="alert">
            {{ Session::get('sukses') }}
        </div>
        @endif
        @if(Session::has('gagal'))
        <div class="alert alert-danger" role="alert">
            {{ Session::get('gagal') }}
        </div>
        @endif
        <div class="row">
            <div class="col-md-12 col-sm-12 ">
                <div>
                    <div class="x_title">
                        <div class="d-flex">
                            <div class="mr-auto p-2">
                                @if ($start != null && $end != null)
                                <h2>Laporan Presensi<small>Data Untuk Periode {{$start}} sd {{$end}}</small></h2>
                                @else
                                <h2>Laporan Presensi<small>Data Untuk Semua Periode</small></h2>
                                @endif
                            </div>
                            <div class="p-2">
                                <input type="date" id="date-start" value="{{$start}}">
                            </div>
                            <div class="p-2">
                                sd
                            </div>
                            <div class="p-2">
                                <input type="date" id="date-end" value="{{$end}}">
                            </div>
                            <div class="p-1">
                                <button type="button" class="btn btn-primary" id="btnsearchlaporanpegawai">
                                    Filter
                                </button>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box table-responsive">
                                    <table id="datatable-pegawai" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>Status Kehadian</th>
                                                <th>Periode Mulai Absen</th>
                                                <th>Jam Mulai Absen</th>
                                                <th>Periode Selesai Absen</th>
                                                <th>Jam Selesai Absen</th>
                                                <th> Total Jam Normal </th>
                                                <th> Total Jam Aktual </th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($data as $key => $value)
                                            <tr>
                                                <td>{{$key+1}}</th>
                                                <td>{{$value->username}}</td>
                                                <td>{{$value->status}}</td>

                                                <td>{{$value->jam_mulai}}</td>

                                                @if ($value->jam_absen_masuk == null)
                                                <td>Tidak Ada</td>
                                                @else
                                                <td>{{$value->jam_absen_masuk}}</td>
                                                @endif

                                                <td>{{$value->jam_akhir}}</td>

                                                @if ($value->jam_absen_keluar == null)
                                                <td>Tidak Ada</td>
                                                @else
                                                <td>{{$value->jam_absen_keluar}}</td>
                                                @endif

                                                @if($value->totaljamnormal == null)
                                                <td>Tidak Ada</td>
                                                @else
                                                <td>{{$value->totaljamnormal}} Jam</td>
                                                @endif

                                                @if($value->totalaktual == null)
                                                <td>Tidak Ada</td>
                                                @else
                                                <td>{{$value->totalaktual}} Jam</td>
                                                @endif
                                            </tr>
                                            @endforeach
                                            <!-- <tr>
                                                <td style="display: none">a</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td><b>Total: </b> </td>
                                                <td>{{$totaljamnormal}} Jam</td>
                                                <td>{{$totaljamaktual}} Jam</td>
                                            </tr>
                                            <tr>
                                                <td>a</td>
                                                <td><b>Total Hari Hadir</b> {{$totalhadir}} Hari</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                            </tr>
                                            <tr>
                                                <td>a</td>
                                                <td><b>Total Hari Bolos</b> {{$totalbolos}} Hari</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                            </tr>
                                            <tr>
                                                <td>a</td>
                                                <td><b>Total Hari Sakit</b> {{$totalsakit}} Hari</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                            </tr>
                                            <tr>
                                                <td>a</td>
                                                <td><b>Total Hari Libur</b> {{$totallibur}} Hari</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                                <td>a</td>
                                            </tr> -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer" id="export-container">

    </div>
</div>

@endsection