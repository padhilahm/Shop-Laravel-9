{{-- @dd(session('cart')) --}}
{{-- {{ session('cart')[0]['name'] }} --}}
{{-- {{ var_dump(session('cart')) }} --}}

@extends('layouts.app')
@include('layouts.nav')

@section('container')
<div class="container px-4 px-lg-5 mt-5">
    <form action="/checkout-buyer" method="POST">
        @csrf
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="inputEmail4">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                @error('email')
                <small id="emailHelp" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group col-md-6">
                <label for="inputPassword4">Phone</label>
                <input type="number" class="form-control" id="phone" name="phone" placeholder="Phone">
                @error('phone')
                <small id="emailHelp" class="form-text text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>
        <div class="form-group">
            <label for="inputAddress">Name</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Name">
            @error('name')
            <small id="emailHelp" class="form-text text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="text-right">
            <button type="submit" class="btn btn-outline-dark mb-5">Pay</button>
        </div>
    </form>
</div>
@endsection