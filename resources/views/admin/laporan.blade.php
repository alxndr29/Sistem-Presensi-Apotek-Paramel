@extends('layouts.templateadmin')

@section('content')
<div class="card">
    <div class="card-header">
        Laporan Presensi
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
                        <div class="d-flex justify-content-between">
                            <div class="mx-1">
                                <h2>Laporan<small>Data Untuk Periode Aktif Saat Ini {{$periode->jam_mulai}} sd {{$periode->jam_akhir}}</small></h2>
                            </div>
                            <div class="mx-1">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                                    Filter
                                </button>
                            </div>
                            <!-- <div class="mx-1">
                                <div class="form-group">
                                    <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1" name="jam_mulai" />
                                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mx-1 my-auto">
                                sd
                            </div>
                            <div class="mx-1">
                                <div class="form-group">
                                    <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                                        <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker2" name="jam_akhir" />
                                        <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
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
                                                <th> Ubah </th>
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
                                                <td>{{$value->jam_absen_masuk}} Jam</td>
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
                                                <td>
                                                    <button type="button" class="btn btn-primary" onClick="detailPresensiUbah({{$value->iduser}},{{$value->idperiode}})">
                                                        Ubah
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
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
<br>
<br>
<div class="card">
    <div class="card-header">
        Notifikasi Tidak Hadir (sementara)
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
                        <div class="d-flex justify-content-between">
                            <div class="mx-1">
                                <h2>Notifikasi<small>Ketidakharian pada semua periode</small></h2>
                            </div>
                            <div class="mx-1">
                                <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalCenter">
                                    Filter
                                </button> -->
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-box table-responsive">
                                    <table id="datatable-pegawai-2" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th> Total Ketidakhadiran </th>
                                                <th> Reset </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($ketidakhadiran as $key => $value)
                                            <tr>
                                                <td>{{$key+1}}</th>
                                                <td>{{$value->username}}</th>
                                                <td>{{$value->totaltidakhadir}}</th>
                                                <td>
                                                    <button type="button" class="btn btn-primary">
                                                        Reset Notifikasi
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
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
    <div class="card-footer" id="export-container-2">

    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="edit-presensi" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Presensi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="#" id="form-edit-presensi">
                @csrf
                @method('put')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Status Kehadiran </label>
                        <select class="form-control" id="comboboxkehadiranedit" name="status_kehadiran">
                            <option value="Hadir">Hadir</option>
                            <option value="Tidak Hadir">Tidak Hadir</option>
                            <option value="Libur">Libur</option>
                            <option value="Sakit">Sakit</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Jam Mulai Absen</label>
                        <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                            <input id="jammulaiedit" type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1" name="jam_mulai" />
                            <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Jam Selesai Absen</label>
                        <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                            <input id="jamselesaiedit" type="text" class="form-control datetimepicker-input" data-target="#datetimepicker2" name="jam_akhir" />
                            <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Hello World</label>
                    <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1" name="jam_mulai" />
                        <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Hello World</label>
                    <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                        <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker2" name="jam_akhir" />
                        <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                            <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection