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
@if( $status_presensi->status == "Hadir" && $status_presensi->jam_absen_keluar == null)
<div class="alert alert-success alert-dismissible text-center" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
    <strong>Jam Kerja Hari Ini Sampai Pukul: {{$periode_presensi->jam_akhir}}</strong>
</div>
@endif

@endif

@if($tombol_absen == false && $periode_presensi != null && $status_presensi->status != "Hadir")
<div class="alert alert-danger alert-dismissible text-center" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
    </button>
    <strong>Anda Tidak Diperbolehkan Melakukan Absensi Karena Melewati Batas Waktu</strong>
</div>
@endif

<div class="row">
    <div class="col-md-3   widget widget_tally_box mx-auto">
        <div class="x_panel fixed_height_390" style="height:auto;">
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
                    @if($periode_presensi != null)
                    @if( $status_presensi->status == "Hadir")
                    @if($status_presensi->jam_absen_keluar == null)
                    <h3 class="mx-auto"> Anda Tercatat Hadir </h3>
                    <a href="{{route('pegawai.presensikeluar')}}" class="btn btn-primary mx-auto">KELUAR</a>
                    @else
                    <h3 class="mx-auto text-center">Sudah Melakukan Presensi Keluar</h3>
                    @endif
                    @else
                    @if($tombol_absen == true)
                    <a href="{{route('pegawai.presensimasuk')}}" id="btnabsen" class="btn btn-primary mx-auto disabled">ABSEN</a>
                    @else
                    <a href="{{route('pegawai.presensimasuk')}}" id="btnabsen" class="btn btn-primary mx-auto disabled">ABSEN</a>
                    @endif
                    @endif
                    @endif
                </div>
                <div class="row">
                    <div class="col" id="map-containerrr">
                        <div id="map" style="height: 300px;"></div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('anotherjs')
<script type="text/javascript">
    var markerApotek;
    var markerPegawai;

    var latitudePegawai;
    var longitudePegawai;

    $(document).ready(function() {
        getLocation();
    });

    function showMap() {
        var map = L.map('map').setView(["{{$lokasi->latitude}}", "{{$lokasi->longitude}}"], 7);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);
        markerPegawai = L.marker([latitudePegawai, longitudePegawai]).bindTooltip("Pegawai", {
            permanent: true
        }).addTo(map);
        markerApotek = L.marker(["{{$lokasi->latitude}}", "{{$lokasi->longitude}}"]).bindTooltip("Apotek", {
            permanent: true
        }).addTo(map);

        var jarak = jarakKeTujuan("{{$lokasi->latitude}}", "{{$lokasi->longitude}}", latitudePegawai, longitudePegawai);

        if (parseInt(jarak) > parseInt("{{$lokasi->minimal_jarak}}")) {
            console.log('jauh ga bisa absen');
            $('#btnabsen').addClass("disabled");
        } else {
            console.log('mntp bisa absen');
            $('#btnabsen').removeClass("disabled");
            // $("#btnabsen").prop("disabled", false);
        }
        $("#map-containerrr").append('Anda Harus Berjarak Maksimal' + "{{$lokasi->minimal_jarak}} Meter" + '. Jarak Anda ke Tujuan: ' + jarak + " Meter");
    }

    function jarakKeTujuan(lat1, lon1, lat2, lon2) {
        function toRad(x) {
            return x * Math.PI / 180;
        }
        var R = 6371; // km
        var x1 = lat2 - lat1;
        var dLat = toRad(x1);
        var x2 = lon2 - lon1;
        var dLon = toRad(x2)
        var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        var d = R * c;
        return d * 1000;
    }

    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(showPosition);
        } else {
            alert("Geolocation is not supported by this browser.");
        }
    }

    function showPosition(position) {
        // Jika ingin bypass
        // latitudePegawai = ISI SENDIRI;
        // longitudePegawai = ISI SENDIRI;
        // Jangan Lupa Comment 2 dibawah
        latitudePegawai = position.coords.latitude;
        longitudePegawai = position.coords.longitude;
        showMap();
    }
    //https://stackoverflow.com/questions/14560999/using-the-haversine-formula-in-javascript
    //https://www.w3schools.com/html/html5_geolocation.asp
</script>

@endsection