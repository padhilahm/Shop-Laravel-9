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
            {{-- {{ $product->category }}

            @foreach ($categories as $category)
            {{ $category->name }}
            @endforeach --}}
            @if ($setting)
            <form action="/setting-update" method="POST">
            @else
            <form action="/setting" method="POST">
            @endif
                @csrf
                @if ($setting)
                @method('put')
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Server Key Payment</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="serverKey" name="serverKey" placeholder="Server Key" value="{{ old('serverKey', $serverKey->value) }}">
                        @error('serverKey')
                            <small id="emailHelp" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Client Key Payment</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="clientKey" name="clientKey" placeholder="Client Key" value="{{ old('clientKey', $clientKey->value) }}">
                        @error('clientKey')
                            <small id="emailHelp" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                @else
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Server Key Payment</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="serverKey" name="serverKey" placeholder="Server Key" value="{{ old('serverKey') }}">
                        @error('serverKey')
                            <small id="emailHelp" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Client Key Payment</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="clientKey" name="clientKey" placeholder="Client Key" value="{{ old('clientKey') }}">
                        @error('clientKey')
                            <small id="emailHelp" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                @endif
                
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