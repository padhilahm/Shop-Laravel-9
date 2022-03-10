@extends('layouts.app')

@section('container')
 {{-- {{ request('search') }} --}}
<div class="container px-4 px-lg-5">
    <div class="row justify-content-center mb-3">
        <div class="col-md-6">
            @if (Request::is('category*'))
            <form action="/category/{{ $slug }}" method="get">
            @else
            <form action="/product" method="get">
            @endif
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Search..." name="search"
                        value="{{ request('search') }}">
                    <button class="btn btn-outline-dark mt-auto" type="submit">Search</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
        @foreach ($products as $product)
        <div class="col mb-5">
            <div class="card h-100">
                <!-- Product image-->
                @if ($product->image)
                <img class="card-img-top" src="/storage/{{ $product->image }}" alt="..." />
                @else
                <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                @endif
                <!-- Product details-->
                <div class="card-body p-4">
                    <div class="text-center">
                        <!-- Product name-->
                        <h5 class="fw-bolder">{{ $product->name }}</h5>
                        <!-- Product price-->
                        Rp.{{ $product->price }}
                    </div>
                </div>
                <!-- Product actions-->
                <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                    <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="product/{{ $product->id }}">Lihat detail</a></div>
                </div>
            </div>
        </div>
            
        @endforeach

    </div>
    {{ $products->links() }}
</div>
@endsection