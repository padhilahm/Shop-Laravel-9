{{-- {{ session('productStock') }} --}}
{{-- @dd(session('overStock')) --}}

@extends('layouts.app')

@section('container')
<div class="container px-4 px-lg-5 mt-5">
    @if (session('overStock'))
    <div class="alert alert-danger" id="overStock" role="alert">
        {{ session('overStock') }}
      </div>
    @endif
    @if (session()->has('error'))
    <div class="alert alert-danger" id="overStock" role="alert">
        {{ session('error') }}
      </div>
    @endif
    <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
        <table id="cart" class="table table-hover table-condensed">
            <thead>
                <tr>
                    <th style="width:50%">Produk</th>
                    <th style="width:10%">Harga</th>
                    <th style="width:8%">Jumlah</th>
                    <th style="width:22%" class="text-center">Subtotal</th>
                    <th style="width:10%"></th>
                </tr>
            </thead>
            <tbody id="dataView">

                @php $total = 0 @endphp
                @if(session('cart'))
                @foreach(session('cart') as $id => $details)
                @php $total += $details['price'] * $details['quantity'] @endphp
                <tr id="rowData">
                    <td data-th="Product" id="product">
                        <div class="row">

                            <div class="col-sm-3 hidden-xs">
                                @if ($details['image'])
                                <img src="/storage/{{ $details['image'] }}" width="100" height="100"
                                    class="img-responsive" />
                                @else
                                <img src="https://dummyimage.com/100x100/dee2e6/6c757d.jpg" width="100" height="100"
                                    class="img-responsive" />
                                @endif
                            </div>
                            <div class="col-sm-9">
                                <h4 class="nomargin">{{ $details['name'] }}</h4>
                            </div>
                        </div>
                    </td>
                    <td data-th="Price">Rp.{{ $details['price'] }}</td>
                    <td data-th="Quantity">
                        <input type="number" value="{{ $details['quantity'] }}" class="form-control quantity"
                            id="quantity{{ $id }}" onchange="updateCart({{ $id }})" />
                    </td>
                    <td data-th="Subtotal" class="text-center">Rp.{{ $details['price'] * $details['quantity'] }}</td>
                    <td class="actions" data-th="">
                        <button class="btn btn-danger btn-sm remove-from-cart" onclick="deleteCart({{ $id }})"><i
                                class="fa fa-trash-o"></i></button>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right">
                        <h3><strong id="total">Total Rp.{{ $total }}</strong></h3>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" class="text-right">
                        <a href="{{ url('/') }}" class="btn btn-outline-dark"><i class="fa fa-angle-left"></i> Lanjutkan Belanja</a>
                        <a href="/checkout-buyer"><button class="btn btn-outline-dark">Checkout</button></a>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    function dataView(msg) {
        $('#dataView').html('');
        $.each( msg, function( key, value ) {
            $("#dataView").append(`<tr id="rowData">
                    <td data-th="Product" id="product">
                        <div class="row">
                            <div class="col-sm-3 hidden-xs">
                                <img src="${value.image}" width="100" height="100"
                                    class="img-responsive" />
                            </div>
                            <div class="col-sm-9">
                                <h4 class="nomargin">${value.name}</h4>
                            </div>
                        </div>
                    </td>
                    <td data-th="Price">Rp.${value.price}</td>
                    <td data-th="Quantity">
                        <input type="number" value="${value.quantity}"
                            class="form-control quantity" id="quantity${value.id}" onchange="updateCart(${value.id})" />
                    </td>
                    <td data-th="Subtotal" class="text-center">Rp.${value.price * value.quantity}</td>
                    <td class="actions" data-th="">
                        <button class="btn btn-danger btn-sm remove-from-cart" onclick="deleteCart(${value.id})"><i class="fa fa-trash-o"></i></button>
                    </td>
                </tr>`);
        });
    }

    function updateCart(id) {
        var id = id;
        var quantity = $('#quantity'+id).val();
        $.ajax({
            url: '{{ route('update.cart') }}',
            method: "patch",
            type: 'JSON',
            data: {
                _token: '{{ csrf_token() }}', 
                id: id,
                quantity: quantity
            },
            success: function (response) {
                dataView(response.dataView);
                $('#total').html(`Total Rp.${response.total}`);
                $('#overStock').css('display','none');
            }
        });
    }
    
    function deleteCart(id) {
        var id = id;
        if(confirm("Are you sure want to remove?")) {
            $.ajax({
                url: '{{ route('remove.from.cart') }}',
                method: "DELETE",
                type: 'JSON',
                data: {
                    _token: '{{ csrf_token() }}', 
                    id: id
                },
                success: function (response) {
                    // $('#dataView').html(response.dataView);
                    dataView(response.dataView);
                    $('#total').html(`Total Rp.${response.total}`);
                    $('#totalCart').html(response.totalCart);
                    $('#overStock').css('display','none');

                }
            });
        }   
    }
  
</script>
@endsection