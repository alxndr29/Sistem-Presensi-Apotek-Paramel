@extends('layouts.templateadmin')

@section('content')

<div class="card">
    <div class="card-header">
        Daftar Periode Presensi
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
                        <h2>Daftar Periode<small></small></h2>
                        <ul class="nav navbar-right panel_toolbox">
                            <li>
                                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#tambah-periode">
                                    Tambah Periode
                                </button>
                            </li>

                        </ul>
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
                                                <th>Status</th>
                                                <th>Jam Mulai</th>
                                                <th>Jam Akhir</th>
                                                <th>Detail</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($periode_presensi as $key => $value)
                                            <tr>
                                                <td>{{$key+1}}</td>
                                                @if ($value->aktif == 1)
                                                <td>Aktif</td>
                                                @else
                                                <td>Tidak Aktif</td>
                                                @endif
                                                <td>{{$value->jam_mulai}}</td>
                                                <td>{{$value->jam_akhir}}</td>
                                                <td>
                                                    <a href="{{route('admin.periodedetail',$value->idperiode)}}" class="btn btn-success">Detail</a>
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
<!-- Modal Tambah Periode -->
<div class="modal fade" id="tambah-periode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Periode</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('admin.storeperiode')}}" method="post" onsubmit="return validate(this);">
                @csrf
                @method('post')
                <div class="modal-body">
                    <div class="form-group">
                        <label>Tanggal & Jam Awal Periode</label>
                        <div class="input-group date" id="datetimepicker1" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker1" name="jam_mulai" value="{{date('Y-m-d')}} 08:00:00" />
                            <div class="input-group-append" data-target="#datetimepicker1" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Tanggal & Jam Akhir Periode</label>
                        <div class="input-group date" id="datetimepicker2" data-target-input="nearest">
                            <input type="text" class="form-control datetimepicker-input" data-target="#datetimepicker2" name="jam_akhir" value="{{date('Y-m-d')}} 17:00:00" />
                            <div class="input-group-append" data-target="#datetimepicker2" data-toggle="datetimepicker">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    function validate(form) {
        var valid = true;
        if (!valid) {
            alert('Please correct the errors in the form!');
            return false;
        } else {
            return confirm('Simpan Perubahan?');
        }
    }
</script>
@endsection
<!-- https://getdatepicker.com/5-4/Usage/ -->