{{-- @dd(session('cart')[1]) --}}

@extends('layouts.app')

@section('container')

<div class="container px-4 px-lg-5 mt-5">


    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <div id="alert"></div>
    <div class="container px-4 px-lg-5 my-5">
        <div class="row gx-4 gx-lg-5 ">
            <div class="col-md-6">
                @if ($product->image)
                <img class="card-img-top mb-5 mb-md-0" src="/storage/{{ $product->image }}" alt="..." />
                @else
                <img class="card-img-top mb-5 mb-md-0" src="https://dummyimage.com/600x700/dee2e6/6c757d.jpg"
                    alt="..." />
                @endif
            </div>
            <div class="col-md-6">
                {{-- <div class="small mb-1">{{ $product->name }}</div> --}}
                <h1 class="display-5 fw-bolder">{{ $product->name }}</h1>
                <div class="fs-5 mb-4">
                    Harga <br>
                    <span>Rp.{{ number_format($product->price) }}</span>
                </div>
                <div class="fs-5 mb-4">
                    Stok <br>
                    <span>{{ $product->stock }}</span>
                </div>
                
                @if ($product->stock == 0)
                <div class="text-danger">Stok habis</div>
                @else

                <div class="d-flex">
                    <input class="form-control text-center me-3" id="quantity" type="number" value="1" min="1"
                        max="{{ $product->stock }}" style="max-width: 5rem" onchange="productStock()" />
                    <button class="btn btn-outline-dark flex-shrink-0" type="button"
                        onclick="addCart({{ $product->id }})">
                        <i class="bi-cart-fill me-1"></i>
                        Tambah
                    </button>
                </div>
                @endif
                <br>
                <h5>Deskripsi</h5>
                <p class="lead">{!! $product->description !!}</p>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script type="text/javascript">
    function addCart(id) {
        var id = id;
        var quantity = $('#quantity').val();
        if (quantity != 0) {
            $.ajax({
                url: '{{ route('add.to.cart') }}',
                method: "post",
                type: 'JSON',
                data: {
                    _token: '{{ csrf_token() }}', 
                    id: id,
                    quantity: quantity
                },
                success: function (response) {
                    if (response.code === 400) {
                        alert(response.message);
                    }else{
                        console.log(response.quantity);
                        $('#totalCart').html(response.totalCart);
                        // $('#alert').html(response.alert);
                        alert(response.message);
                    }
                }
            });
        }else{
            alert('Masukkan jumlah dibeli');
        }
    }
  
</script>
@endsection