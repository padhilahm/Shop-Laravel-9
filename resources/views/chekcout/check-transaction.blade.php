@extends('layouts.app')
<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $clientKey }}">
</script>
@section('container')
<div class="container px-4 px-lg-5 mt-5">

    <small id="errorAll" class="form-text text-danger"></small>
    <br>
    <div class="alert alert-danger print-error-msg" style="display:none">
        <ul></ul>
    </div>
    <div class="form-group row">
        <div class="form-group col-md-6">
            <label for="inputEmail4">No Pembayaran</label>

            <input type="text" class="form-control" id="paymentId" name="paymentId" placeholder="No Pembayaran"
                value="{{ request('no') ? request('no') : '' }}">

            <small id="paymentIdError" class="form-text text-danger"></small>
            <button type="submit" id="check" class="btn btn-outline-dark mb-5">Cek</button>
        </div>

        <div class="card h-100" id="paymentTemplate">
        </div>

    </div>

</div>
@endsection

@section('scripts')
<script type="text/javascript">
    $('#check').click(function (event) {
    event.preventDefault();
    var paymentId = $('#paymentId').val();
    $('#paymentTemplate').html('');
    $('#errorAll').html('');
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
            }else if(data.dataPayment === null){
                $('#errorAll').html('No pembayaran tidak tersedia');
            }else{
                let products = data.dataProducts;
                var product_ = '';
                var total = 0;
                if (data.dataPayment.shipping_type_id == 1) {
                    var paymentType = 'Diantar kerumah';
                }else{
                    var paymentType = 'Ambil sendiri';
                }

                if (data.dataPayment.status == 201) {
                    var paymentStatus = 'Tertunda';
                } else if(data.dataPayment.status == 200){
                    var paymentStatus = 'Dibayar';
                }else{
                    var paymentStatus = 'Dibatalkan';
                }

                if (data.dataPayment.shipping == 0 && data.dataPayment.address == null) {
                    var paymentShipping = '';
                    var paymentAddress = '';
                    var shop = `<div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Pemberitahuan</label>
                        <div class="col-sm-10">
                            : Silahkan ambil pesanan Anda di alamat ${data.dataShop.address} atau maps berikut <a href="${data.dataShop.maps}" target="_blank">${data.dataShop.maps}</a>
                        </div>
                    </div>`;
                } else {
                    var paymentShipping = `<div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Jasa Pengantaran</label>
                        <div class="col-sm-10">
                            Rp.${data.dataPayment.shipping}
                        </div>
                    </div>`;
                    var paymentAddress = `<div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Alamat</label>
                        <div class="col-sm-10">
                            : ${data.dataPayment.address}
                        </div>
                    </div>`;
                    var shop = '';
                }

                if (data.dataPayment.token == null) {
                    var paymentToken = '';
                    var paymentStatus_ = '';
                }else{
                    var paymentToken = `<div class="form-group row">
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <input type="hidden" id="token" value="${data.dataPayment.token}">
                            <div class="text-right">
                                <button onclick="snapPay()" type="submit" id="check" class="btn btn-outline-dark mb-5">Cek Pembayaran</button>
                            </div>
                        </div>

                    </div>`;
                    var paymentStatus_ = `<div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Status</label>
                        <div class="col-sm-10">
                            : ${paymentStatus}
                        </div>
                    </div>`;
                }

                products.forEach(product => {
                    product_ += `<tr>
                            <td>${product.name}</td>
                            <td>Rp.${product.price}</td>
                            <td>${product.quantity}</td>
                        </tr>`;
                    total += (product.price * product.quantity);
                });
                total += data.dataPayment.shipping;
                $('#paymentTemplate').html(`<div class="panel panel-default">
                <div class="panel-body">
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">No Pembayaran</label>
                        <div class="col-sm-10">
                            : ${data.dataPayment.id}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Jenis Pengantaran</label>
                        <div class="col-sm-10">
                            : ${paymentType}
                        </div>
                    </div>
                    ${shop}
                    ${paymentStatus_}
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            : ${data.dataBuyer.name}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                        <div class="col-sm-10">
                            : ${data.dataBuyer.email}
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">No HP</label>
                        <div class="col-sm-10">
                            : ${data.dataBuyer.phone}
                        </div>
                    </div>
                    ${paymentAddress}
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Produk</label>
                        <div class="col-sm-10">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">Nama</th>
                                        <th scope="col">Harga</th>
                                        <th scope="col">Jumlah</th>
                                    </tr>
                                </thead>
                                ${product_}
                            </table>
                        </div>
                    </div>
                    ${paymentShipping}
                    <div class="form-group row">
                        <label for="inputEmail3" class="col-sm-2 col-form-label">Total Pembayaran</label>
                        <div class="col-sm-10">
                            Rp.${total}
                        </div>
                    </div>
                    
                    ${paymentToken}
                </div>
            </div>`);
            }
        }
    });
});
</script>

<script>
    function snapPay() {
        var token = $('#token').val();
        snap.pay(token, {
            onSuccess: function(result){
                changeResult('success', result);    
                console.log(result.status_message);
                console.log(result);
                // $("#payment-form").submit();
            },
            onPending: function(result){
                changeResult('pending', result);
                console.log(result.status_message);
                // $("#payment-form").submit();   
            },
            onError: function(result){
                changeResult('error', result);
                console.log(result.status_message);
                // $("#payment-form").submit();
            }
        });
    }
</script>
@endsection