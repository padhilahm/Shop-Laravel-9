{{-- @dd($setting) --}}
@extends('layouts-admin.app')

@section('container')
<div class="col-md-2 sidebar">
    <div class="row">
        <!-- uncomment code for absolute positioning tweek see top comment in css -->
        <div class="absolute-wrapper"> </div>
        <!-- Menu -->
        @include('layouts-admin.menu')
    </div>
</div>
@if (session()->has('success'))
<div class="alert alert-success col-lg-10" role="alert">
    {{ session('success') }}
</div>
@elseif (session()->has('error'))
<div class="alert alert-danger col-lg-10" role="alert">
    {{ session('error') }}
</div>

@endif
<div class="col-md-10 content">
    <div class="panel panel-default">
        <div class="panel-heading">
            Settings
        </div>
        <div class="panel-body">
            <form action="/setting-shop" method="POST">
                @csrf
                @method('put')
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Shop Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="shopName" name="shopName" placeholder="Shop Name"
                            value="{{ old('shopName', $shopName->value) }}">
                        @error('shopName')
                        <small id="emailHelp" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Address</label>
                    <div class="col-sm-10">
                        <textarea name="address" class="form-control" id="address" cols="30" rows="10">{{ old('address', $address->value) }}</textarea>
                        @error('address')
                        <small id="emailHelp" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection