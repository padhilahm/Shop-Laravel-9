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
            <form action="/setting-payment-type" method="POST">
                @csrf
                @method('put')
                <div class="form-group row">
                    <label for="inputdirect3" class="col-sm-2 col-form-label">Bayar Langsung</label>
                    <div class="col-sm-10">

                        <select name="direct" id="direct" class="form-control">
                            <option value="true" @if (old('direct', $direct->value) == 'true')
                                selected
                            @endif>
                                Aktifkan
                            </option>
                            <option value="false" @if (old('direct', $direct->value) == 'false')
                                selected
                            @endif>
                                Non Aktifkan
                            </option>
                        </select>

                        @error('direct')
                        <small id="directHelp" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputdirect3" class="col-sm-2 col-form-label">Bayar Ditempat(COD)</label>
                    <div class="col-sm-10">
                        <select name="cod" id="cod" class="form-control">
                            <option value="true" @if (old('cod', $cod->value) == 'true')
                                selected
                            @endif>
                                Aktifkan
                            </option>
                            <option value="false" @if (old('cod', $cod->value) == 'false')
                                selected
                            @endif>
                                Non Aktifkan
                            </option>
                        </select>

                        @error('cod')
                        <small id="codHelp" class="form-text text-danger">{{ $message }}</small>
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