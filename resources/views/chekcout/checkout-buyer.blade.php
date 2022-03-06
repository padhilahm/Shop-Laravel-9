{{-- @dd($shippingPrices->count()) --}}
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
                <label for="inputPassword4">No HP</label>
                <input type="number" class="form-control" id="phone" name="phone" placeholder="No HP">

                <small id="phoneError" class="form-text text-danger"></small>

            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputEmail4">Nama</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nama">
                <small id="nameError" class="form-text text-danger"></small>

            </div>
            <div class="form-group col-md-6">
                <label for="inputPassword4">Jenis Pengiriman</label>
                <select name="shippingType" id="shippingType" class="form-control" onchange="shippingType()">
                    <option value=""> - Pilih - </option>
                    <option value="1">Kurir</option>
                    <option value="2">Ambil Sendiri</option>
                </select>

                <small id="shippingTypeError" class="form-text text-danger"></small>

            </div>
        </div>

        {{-- <div class="form-group">
            <label for="inputAddress">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Name">
            <small id="nameError" class="form-text text-danger"></small>

        </div> --}}

        <div id="courierType" style="display:none">
            <div class="form-group">
                <label for="inputAddress">Alamat</label>
                <textarea name="address" id="address" class="form-control"></textarea>
                <small id="addressError" class="form-text text-danger"></small>
            </div>

            <div class="form-group">
                <label for="inputAddress">Pilih Lokasi Pengantaran</label>
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
        </div>


        <div class="text-right">
            <button type="submit" id="pay-button" class="btn btn-outline-dark mb-5">Bayar</button>
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
    function shippingType() {
        var type = $('#shippingType').val();
        if (type == 1) {
            $('#courierType').css('display','block');
            maps();
        }else{
            $('#courierType').css('display','none');
        }
    }
</script>

<script>
    function distance(lat1, lon1, lat2, lon2) {
        var R = 6371; // Radius of the earth in km
        var dLat = deg2rad(lat2-lat1);  // deg2rad below
        var dLon = deg2rad(lon2-lon1); 
        var a = 
            Math.sin(dLat/2) * Math.sin(dLat/2) +
            Math.cos(deg2rad(lat1)) * Math.cos(deg2rad(lat2)) * 
            Math.sin(dLon/2) * Math.sin(dLon/2)
            ; 
        var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
        var d = R * c; // Distance in km
        return d;
        // return lat1;
    }

    function deg2rad(deg) {
        return deg * (Math.PI/180)
    }
</script>



<script>
    // $(document).ready(function() {
    //     getSetting();
    // });

    function maps() {
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
    
    // function getSetting() {
    //     L.marker([-0.21973 , 118.91602], {icon: markIcon}).addTo(map).bindPopup(`My shop location`).openPopup();
    // }
    
    var theMarker = {};
    // get coordinate
    map.on('click',function(e){
        var coord = e.latlng.toString().split(',');
        var lat = coord[0].split('(');
        var lng = coord[1].split(')');
        var distance_ = distance(lat[1], lng[0], {{ $latitude }}, {{ $longitude }});
        
        if (distance_ <= {{ $shippingMax }}) {

            if (distance_ >= {{ $shippingPrices[0]->distince }} && distance_ <= {{ $shippingPrices[1]->distince }}) {
                var shipping = {{ $shippingPrices[0]->price }};
            }

            @for ($i = 1 ; $i < $shippingPrices->count(); $i++)
            @if ($i == $shippingPrices->count()-1)
                if (distance_ > {{ $shippingPrices[$i]->distince }} ) {
                    var shipping = {{ $shippingPrices[$i]->price }};
                }
            @else
                if (distance_ > {{ $shippingPrices[$i]->distince }} && distance_ <= {{ $shippingPrices[$i+1]->distince }}) {
                    var shipping = {{ $shippingPrices[$i]->price }};
                }
            @endif
            @endfor
            
            alert(`Biaya kurir untuk lokasi Anda Rp.${shipping}`);
        }else{
            alert('Mohon maaf lokasi Anda masih belum terjangkau untuk pengantaran secara langsung');
        }

        if (theMarker != undefined) {
                map.removeLayer(theMarker);
        };
        theMarker = L.marker([lat[1], lng[0]]).addTo(map);
        
        $('#latitude').val(lat[1]);
        $('#longitude').val(lng[0]);
        });
    }
</script>

<script type="text/javascript">
    function printErrorMsg (msg) {
        $(".print-error-msg").find("ul").html('');
        $(".print-error-msg").css('display','block');
        $.each( msg, function( key, value ) {
            $(".print-error-msg").find("ul").append(`<li>${value}</li>`);
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
    var shippingType = $('#shippingType').val();
    
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
            address: address,
            shippingType: shippingType
        },
        success: function(data) {
            // console.log(data);
            if (data.code === 400) {
                $('#pay-button').removeAttr("disabled");
                $('#pay-button').html(`Bayar`);
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
                $(".print-error-msg").css('display','none');
            }
            $('#pay-button').removeAttr("disabled");
            $('#pay-button').html(`Bayar`);
        }
    });
});
</script>
@endsection