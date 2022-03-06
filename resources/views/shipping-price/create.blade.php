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

<div class="col-md-10 content">
    <div class="panel panel-default">
        <div class="panel-heading">
            Shipping Price
        </div>
        <div class="panel-body">

            <form action="/shipping-price" method="POST">
                @csrf
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Distince (KM)</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="distince" name="distince" placeholder="distince" value="{{ old('distince') }}">
                        @error('distince')
                            <small id="emailHelp" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Price (Rp)</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="price" name="price" placeholder="price" value="{{ old('price') }}">
                        @error('price')
                            <small id="emailHelp" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Create</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection