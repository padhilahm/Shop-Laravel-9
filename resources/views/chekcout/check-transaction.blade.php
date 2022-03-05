@extends('layouts.app')
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}">
</script>
@section('container')
<div class="container px-4 px-lg-5 mt-5">

    @csrf
    <small id="errorAll" class="form-text text-danger"></small>
    <div class="alert alert-danger print-error-msg" style="display:none">
        <ul></ul>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="inputEmail4">No Payment</label>

            <input type="text" class="form-control" id="paymentId" name="paymentId" placeholder="No Payment" value="{{ isset($_GET['no']) ? $_GET['no'] : '' }}">

            <small id="paymentIdError" class="form-text text-danger"></small>
        </div>

    </div>
    <button type="submit" id="check" class="btn btn-outline-dark mb-5">Check</button>

</div>
@endsection

@section('scripts')
<script type="text/javascript">

$('#check').click(function (event) {
    event.preventDefault();
    var paymentId = $('#paymentId').val();
    
    $.ajax({
        url: '/transaction-check',
        method: 'post',
        cache: false,
        data: {
            _token: '{{ csrf_token() }}',
            paymentId: paymentId
        },
        success: function(data) {
            console.log(data);
            if (data.code === 400) {
                console.log(data.error);
                $('#errorAll').html(data.error);
            }else if(data.data === null){
                $('#errorAll').html('No Payment tidak tersedia');
            }else{
                snap.pay(data.data.token, {
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
        }
    });
});
</script>
@endsection