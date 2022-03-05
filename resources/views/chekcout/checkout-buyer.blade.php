
@extends('layouts.app')
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}">
</script>

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
<div class="container px-4 px-lg-5 mt-5">
    {{-- <form action="/checkout-buyer" method="POST"> --}}

        @csrf
        {{-- <small id="errorAll" class="form-text text-danger"></small> --}}
        <div class="alert alert-danger print-error-msg" style="display:none">
            <ul></ul>
        </div>
        {{-- {{ $distance }} --}}
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputEmail4">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email">

                <small id="emailError" class="form-text text-danger"></small>

            </div>
            <div class="form-group col-md-6">
                <label for="inputPassword4">Phone</label>
                <input type="number" class="form-control" id="phone" name="phone" placeholder="Phone">

                <small id="phoneError" class="form-text text-danger"></small>

            </div>
        </div>
        <div class="form-group">
            <label for="inputAddress">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Name">
            <small id="nameError" class="form-text text-danger"></small>

        </div>
        
        <div class="form-group">
            <label for="inputAddress">Address</label>
            <textarea name="address" id="address" class="form-control"></textarea>
            <small id="addressError" class="form-text text-danger"></small>
        </div>

        <div class="form-group">
            <label for="inputAddress">Maps</label>
            <div id="map"></div>
            <small id="addressError" class="form-text text-danger"></small>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputEmail4">Latitude</label>
                <input type="text" class="form-control" id="latitude" name="latitude">

                <small id="latitudeError" class="form-text text-danger"></small>

            </div>
            <div class="form-group col-md-6">
                <label for="inputPassword4">Longitude</label>
                <input type="text" class="form-control" id="longitude" name="longitude">
                <small id="longitudeError" class="form-text text-danger"></small>

            </div>
        </div>

        <div class="text-right">
            <button type="submit" id="pay-button" class="btn btn-outline-dark mb-5">Pay</button>
        </div>
        {{--
    </form> --}}
</div>
<form id="payment-form" method="post" action="/snap-finish">
    @csrf
    <input type="hidden" name="result_type" id="result-type" value=""></div>
    <input type="hidden" name="result_data" id="result-data" value=""></div>
</form>
@endsection

@section('scripts')
<script>
    // $(document).ready(function() {
    //     getSetting();
    // });
    
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
        // L.marker(e.latlng, {title: 'lokasi saya', icon: markIcon}).addTo(map)
        // .bindPopup("lokasi saya sekarang").openPopup();
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
    
    // function getSetting() {
    //     L.marker([-0.21973 , 118.91602], {icon: markIcon}).addTo(map).bindPopup(`My shop location`).openPopup();
    // }
    
    var theMarker = {};
    // get coordinate
    map.on('click',function(e){
        var coord = e.latlng.toString().split(',');
        var lat = coord[0].split('(');
        var lng = coord[1].split(')');
            // alert(`lokasi ditetapkan dengan kordinat ${lat[1]}, ${lng[0]}`);
            
            if (theMarker != undefined) {
                  map.removeLayer(theMarker);
            };
            theMarker = L.marker([lat[1], lng[0]]).addTo(map);
            
            $('#latitude').val(lat[1]);
            $('#longitude').val(lng[0]);
        });
</script>

<script type="text/javascript">

    function printErrorMsg (msg) {
        $(".print-error-msg").find("ul").html('');
        $(".print-error-msg").css('display','block');
        $.each( msg, function( key, value ) {
            $(".print-error-msg").find("ul").append(`<li>${value}</li>`);
        });
    }
    
    function dataView(msg) {
        $('#rowData').find('#product').html('');
        $.each( msg, function( key, value ) {
            $("#rowData").find("#product").append(`<li>${value}</li>`);
        });
    }

$('#pay-button').click(function (event) {
    event.preventDefault();
    $(this).attr("disabled", "disabled");
    $(this).html(`<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...`);

    var email = $('#email').val();
    var phone = $('#phone').val();
    var name = $('#name').val();
    var address = $('#address').val();
    var latitude = $('#latitude').val();
    var longitude = $('#longitude').val();
    
    $.ajax({
        url: '/snap-token',
        method: 'post',
        cache: false,
        data: {
            _token: '{{ csrf_token() }}',
            email: email,
            phone: phone,
            name: name,
            latitude: latitude,
            longitude: longitude,
            address: address
        },
        success: function(data) {
            // console.log(data);
            if (data.code === 400) {
                $('#pay-button').removeAttr("disabled");
            $('#pay-button').html(`Pay`);
                if (data.error == 'cart') {
                    console.log(data.error);
                    window.location.href = '/'+data.error;
                }else if(data.error === 'over'){
                    alert('Mohon maaf lokasi Anda masih belum terjangkau untuk pengantaran secara langsung');
                }else{
                    console.log(data.error);
                    // $('#errorAll').html(data.error);
                    printErrorMsg(data.error);
                }
            }else{
                var resultType = document.getElementById('result-type');
                var resultData = document.getElementById('result-data');

                function changeResult(type,data){
                    $("#result-type").val(type);
                    $("#result-data").val(JSON.stringify(data));
                    //resultType.innerHTML = type;
                    //resultData.innerHTML = JSON.stringify(data); 
                }

                snap.pay(data, {
                    onSuccess: function(result){
                        changeResult('success', result);    
                        console.log(result.status_message);
                        console.log(result);
                        $("#payment-form").submit();
                    },
                    onPending: function(result){
                        changeResult('pending', result);
                        console.log(result.status_message);
                        $("#payment-form").submit();   
                    },
                    onError: function(result){
                        changeResult('error', result);
                        console.log(result.status_message);
                        $("#payment-form").submit();
                        }
                });
            }
            $('#pay-button').removeAttr("disabled");
            $('#pay-button').html(`Pay`);
        }
    });
});
</script>
@endsection