@extends('layouts.app')

<!-- Navigation-->
@include('layouts.nav')

@section('container')
<div class="container px-4 px-lg-5 mt-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="row gx-4 gx-lg-5 align-items-center">
            <div class="col-md-6"><img class="card-img-top mb-5 mb-md-0"
                    src="https://dummyimage.com/600x700/dee2e6/6c757d.jpg" alt="..." /></div>
            <div class="col-md-6">
                {{-- <div class="small mb-1">tes</div>
                <h1 class="display-5 fw-bolder">tes</h1>
                <div class="fs-5 mb-5">
                    <span>tes</span>
                </div>
                <p class="lead">tes</p>
                <div class="d-flex">
                    <input class="form-control text-center me-3" id="inputQuantity" type="num" value="1"
                        style="max-width: 3rem" />
                    <button class="btn btn-outline-dark flex-shrink-0" type="button">
                        <i class="bi-cart-fill me-1"></i>
                        Add to cart
                    </button>
                </div> --}}
                @if (session()->has('loginError'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('loginError') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                {{-- {{ session('link') }} --}}
                {{-- {{ Request::segment(1) }} --}}
                {{-- {{ $link }} --}}
                <form action="login" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp"
                            placeholder="Enter email" value="{{ old('email') }}">

                        @error('email')
                        <small id="emailHelp" class="form-text text-danger">{{ $message }}</small>
                        
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Password">
                        @error('password')
                        <small id="emailHelp" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Check me out</label>
                    </div>
                    <button type="submit" class="btn btn-outline-dark">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection