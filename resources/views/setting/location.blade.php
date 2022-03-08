{{-- @dd($setting) --}}
@extends('layouts-admin.app')

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
    integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
    crossorigin="" />

<script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"
    integrity="sha512-gZwIG9x3wUXg2hdXF6+rVkLF/0Vi9U8D2Ntg4Ga5I5BZpVkVxlJWbSQtXPSiUTtC0TjtGOmxa1AJPuV0CPthew=="
    crossorigin=""></script>

@section('container')
<style>
    #map {
        float: left;
        height: 50%;
        width: 100%;
    }
</style>
<div class="col-md-2 sidebar">
    <div class="row">
        <!-- uncomment code for absolute positioning tweek see top comment in css -->
        <div class="absolute-wrapper"> </div>
        <!-- Menu -->
        @include('layouts-admin.menu')
    </div>
</div>
@if (session()->has('success'))
<div class="alert alert-success col-lg-10" role="alert">
    {{ session('success') }}
</div>
@elseif (session()->has('error'))
<div class="alert alert-danger col-lg-10" role="alert">
    {{ session('error') }}
</div>
@endif
<div class="col-md-10 content">
    <div class="panel panel-default">
        <div class="panel-heading">
            Settings
        </div>
        <div class="panel-body">
            <form action="/setting-location" method="POST">
                @csrf
                @method('put')

                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Location</label>

                    <label class="col-sm-1 form-label">Latitude</label>
                    <div class="col-sm-4">
                        <input type="text" name="latitude" id="latitude" class="form-control" value="{{ old('latitude', $latitude->value) }}">
                        <small id=er></small>
                        @error('latitude')
                        <small id="er" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <label class="col-sm-1 form-label">Longitude</label>
                    <div class="col-sm-4">
                        <input type="read" name="longitude" id="longitude" class="form-control"
                            value="{{ old('longitude', $longitude->value) }}">
                        @error('longitude')
                        <small id="er" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    
                </div>

                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Map</label>
                    <div class="col-sm-10">
                        <div id="map"></div>
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        getSetting();
    });
    
    var map = L.map('map').setView([-0.21973, 118.91602], 5);
    
    mapLink =
    '<a href="https://openstreetmap.org">OpenStreetMap</a>';
    L.tileLayer(
        'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&amp;amp;copy; ' + mapLink + ' Contributors',
            maxZoom: 18,
        }).addTo(map);
    
    //lokasi sekarang
    map.locate({setView: true, maxZoom: 16});
    function onLocationFound(e) {
        var radius = e.accuracy;
        L.marker(e.latlng, {title: 'lokasi saya', icon: markIcon}).addTo(map)
        .bindPopup("lokasi saya sekarang").openPopup();
        L.circle(e.latlng, radius).addTo(map);
    }
    map.on('locationfound', onLocationFound);
    
    //cek error lokasi
    function onLocationError(e) {
        alert(e.message);
    }
    map.on('locationerror', onLocationError);
    
    // Format Icon
    var markIcon = L.icon({
        iconUrl: '/assets/marker-icon.png',
        iconSize: [20, 30], // size of the icon
    });
    
    function getSetting() {
        L.marker([{{ $latitude->value }} , {{ $longitude->value }}], {icon: markIcon}).addTo(map).bindPopup(`My shop location`).openPopup();
    }
    
    var theMarker = {};
    // get coordinate
    map.on('click',function(e){
        var coord = e.latlng.toString().split(',');
        var lat = coord[0].split('(');
        var lng = coord[1].split(')');
            alert(`lokasi ditetapkan dengan kordinat ${lat[1]}, ${lng[0]}`);
            
            if (theMarker != undefined) {
                  map.removeLayer(theMarker);
            };
            theMarker = L.marker([lat[1], lng[0]]).addTo(map);
            
            $('#latitude').val(lat[1]);
            $('#longitude').val(lng[0]);
        });
</script>

@endsection