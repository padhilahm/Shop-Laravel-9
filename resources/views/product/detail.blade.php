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
        <div class="row gx-4 gx-lg-5 align-items-center">
            <div class="col-md-6">
                @if ($product->image)
                <img class="card-img-top mb-5 mb-md-0" src="/storage/{{ $product->image }}" alt="..." />
                @else
                <img class="card-img-top mb-5 mb-md-0" src="https://dummyimage.com/600x700/dee2e6/6c757d.jpg"
                    alt="..." />
                @endif
            </div>
            <div class="col-md-6">
                <div class="small mb-1">{{ $product->name }}</div>
                <h1 class="display-5 fw-bolder">{{ $product->name }}</h1>
                <div class="fs-5 mb-5">
                    <span>{{ $product->price }}</span>
                </div>
                <p class="lead">{!! $product->description !!}</p>
                <div class="d-flex">
                    <input class="form-control text-center me-3" id="quantity" type="number" value="1" min="1"
                        style="max-width: 5rem" />
                    {{-- <a href="{{ route('add.to.cart', $product->id) }}"> --}}
                        <button class="btn btn-outline-dark flex-shrink-0" type="button" onclick="addCart({{ $product->id }})">
                            <i class="bi-cart-fill me-1"></i>
                            Add to cart
                        </button>
                    {{-- </a> --}}
                </div>
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
                console.log(response.quantity);
                $('#totalCart').html(response.totalCart);
                // $('#alert').html(response.alert);
                alert(response.alert);
            }
        });
    }
  
</script>
@endsection