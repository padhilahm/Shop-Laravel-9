{{-- @dd($products) --}}
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
            Payments
        </div>
        <div class="panel-body">
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">No Payment</label>
                <div class="col-sm-10">
                    : {{ $payment->id }}
                </div>
            </div>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Status</label>
                <div class="col-sm-10">
                    : {{ $payment->status }}
                </div>
            </div>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                    : {{ $buyer->name }}
                </div>
            </div>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    : {{ $buyer->email }}
                </div>
            </div>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Phone</label>
                <div class="col-sm-10">
                    : {{ $buyer->phone }}
                </div>
            </div>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Products</label>
                <div class="col-sm-10">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                            </tr>
                        </thead>
                        @php $total = 0 @endphp
                        @foreach ($products as $product)
                        @php $total += $product->price * $product->quantity @endphp
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>Rp.{{ number_format($product->price) }}</td>
                            <td>{{ $product->quantity }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
            <div class="form-group row">
                <label for="inputEmail3" class="col-sm-2 col-form-label">Payment Total</label>
                <div class="col-sm-10">
                    Rp.{{ number_format($total) }}
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-10">
                    <a href="/payments"><button type="submit" class="btn btn-primary">Back</button></a>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection