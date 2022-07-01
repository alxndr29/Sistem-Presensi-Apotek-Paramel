@extends('layouts.templateadmin')

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
                                    <table id="datatable-pegawai-laporan" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">
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
    <div class="card-footer" id="export-container-laporan">

    </div>
</div>
@section('anotherjs')
<script type="text/javascript">
    $(document).ready(function() {
        var stitle = "";
        if ("{{$start}}" == null || "{{$end}}" == null) {
            stitle = "Laporan Presensi Semua Periode ";
        } else {
            stitle = "Laporan Presensi Periode " + "{{$start}}" + " sd " + "{{$end}}";
        }
        $('#datatable-pegawai-laporan').DataTable({
            "lengthChange": false,
            "bPaginate": false,
            dom: 'lBfrtip',
            buttons: [{
                    extend: 'excel',
                    title: stitle,
                    messageTop: function() {
                        return '\n \n Total Hari Hadir: ' + "{{$totalhadir}} Hari" +
                            '\n \n Total Hari Bolos: ' + "{{$totalbolos}} Hari" +
                            '\n \n Total Hari Sakit: ' + "{{$totalsakit}} Hari" +
                            '\n \n Total Hari Libur: ' + "{{$totallibur}} Hari";
                    },
                    className: 'btn btn-primary m-1',
                    text: 'Export to Excel',
                    exportOptions: {

                    }
                },
                {
                    extend: 'pdf',
                    title: stitle,
                    messageTop: function() {
                        return '\n \n Total Hari Hadir ' + "{{$totalhadir}} Hari" +
                            '\n \n Total Hari Bolos ' + "{{$totalbolos}} Hari" +
                            '\n \n Total Hari Sakit ' + "{{$totalsakit}} Hari" +
                            '\n \n Total Hari Libur ' + "{{$totallibur}} Hari";
                    },
                    className: 'btn btn-primary m-1',
                    text: 'Export to PDF',
                    exportOptions: {

                    }
                }
            ]
        }).buttons().container().appendTo("#export-container-laporan");
    });
    $("#btnsearchlaporanpegawai").on("click", function() {
        var start = $("#date-start").val();
        var end = $("#date-end").val();
        if (start == null || end == null) {
            alert('harap mengisi data tanggal dengan benar');
        } else {
            location.href = "{{url('detail/pegawai')}}/" + "{{$id}}/" + start + "/" + end;
        }
    });
</script>
@endsection

@endsection