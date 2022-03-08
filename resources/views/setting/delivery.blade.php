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
            <form action="/setting-delivery" method="POST">
                @csrf
                @method('put')
                <div class="form-group row">
                    <label for="inputdelivered3" class="col-sm-2 col-form-label">Diantar Kerumah</label>
                    <div class="col-sm-10">

                        <select name="delivered" id="delivered" class="form-control">
                            <option value="true" @if (old('delivered', $delivered->value) == 'true')
                                selected
                            @endif>
                                Aktifkan
                            </option>
                            <option value="false" @if (old('delivered', $delivered->value) == 'false')
                                selected
                            @endif>
                                Non Aktifkan
                            </option>
                        </select>

                        @error('delivered')
                        <small id="deliveredHelp" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputdelivered3" class="col-sm-2 col-form-label">Diambil sendiri</label>
                    <div class="col-sm-10">
                        <select name="take" id="take" class="form-control">
                            <option value="true" @if (old('take', $take->value) == 'true')
                                selected
                            @endif>
                                Aktifkan
                            </option>
                            <option value="false" @if (old('take', $take->value) == 'false')
                                selected
                            @endif>
                                Non Aktifkan
                            </option>
                        </select>

                        @error('take')
                        <small id="takeHelp" class="form-text text-danger">{{ $message }}</small>
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