@extends('layouts.templateadmin')

@section('content')

<div class="card">
    <div class="card-header">
        <!-- Lokasi -->
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
                        <h2>Data Lokasi<small></small></h2>
                        <div class="clearfix"></div>
                    </div>
                    <div>
                        <div class="row">
                            <div class="col">
                                <form method="post" action="{{route('admin.lokasiupdate')}}">
                                    @csrf
                                    @method('put')
                                    <div class="form-group">
                                        <label>Latitude</label>
                                        <input type="text" class="form-control" placeholder="Latitude" required id="latitude" value="{{$data->latitude}}" name="latitude">
                                    </div>
                                    <div class="form-group">
                                        <label>Longitude</label>
                                        <input type="text" class="form-control" placeholder="Longitude" required id="longitude" value="{{$data->longitude}}" name="longitude">
                                    </div>
                                    <div class="form-group">
                                        <label>Mininum Jarak (Meter)</label>
                                        <input type="number" class="form-control" placeholder="Min Jarak" required value="{{$data->minimal_jarak}}" name="minimal_jarak">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <div id="map" style="height: 300px;"></div>
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

@section('anotherjs')
<script type="text/javascript">
    var marker;
    $(document).ready(function() {
        // alert('hello world!');
        var map = L.map('map').setView(["{{$data->latitude}}", "{{$data->longitude}}"], 7);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        marker = L.marker(["{{$data->latitude}}", "{{$data->longitude}}"]).addTo(map);

        function onMapClick(e) {
            console.log(e.latlng);
            $("#latitude").val(e.latlng.lat.toString());
            $("#longitude").val(e.latlng.lng.toString());
            marker.setLatLng(e.latlng);
        }
        map.on('click', onMapClick);
    });
</script>
@endsection

<!-- https://getdatepicker.com/5-4/Usage/ -->