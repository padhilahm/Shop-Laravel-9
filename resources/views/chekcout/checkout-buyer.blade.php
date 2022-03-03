{{-- @dd(session('cart')) --}}
{{-- {{ session('cart')[0]['name'] }} --}}
{{-- {{ var_dump(session('cart')) }} --}}

@extends('layouts.app')
@include('layouts.nav')
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}">
</script>
@section('container')
<div class="container px-4 px-lg-5 mt-5">
    {{-- <form action="/checkout-buyer" method="POST"> --}}
        
        @csrf
        <small id="errorAll" class="form-text text-danger"></small>
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
<script type="text/javascript">
$('#pay-button').click(function (event) {
    event.preventDefault();
    $(this).attr("disabled", "disabled");
    var email = $('#email').val();
    var phone = $('#phone').val();
    var name = $('#name').val();
    
    $.ajax({
        url: '/snap-token',
        method: 'post',
        cache: false,
        data: {
            _token: '{{ csrf_token() }}',
            email: email,
            phone: phone,
            name: name
        },
        success: function(data) {
            //location = data;
            if (data.code === 400) {
                console.log(data.error);
                $('#errorAll').html(data.error);
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
        }
    });
});
</script>
@endsection