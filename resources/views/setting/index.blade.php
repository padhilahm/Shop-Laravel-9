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
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Name</th>
                        <th scope="col" style="width: 100">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            Shop
                        </td>
                        <td>
                            <a href="/setting-shop"><button class="text-warning">Edit</button></a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Maps
                        </td>
                        <td>
                            <a href="/setting-location"><button class="text-warning">Edit</button></a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Shipping Price
                        </td>
                        <td>
                            <a href="/shipping-price"><button class="text-warning">Edit</button></a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Payment Gateway
                        </td>
                        <td>
                            <a href="/setting-payment"><button class="text-warning">Edit</button></a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Email Sender
                        </td>
                        <td>
                            <a href="/setting-email"><button class="text-warning">Edit</button></a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Delivery Service
                        </td>
                        <td>
                            <a href="/setting-delivery"><button class="text-warning">Edit</button></a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Payment Type
                        </td>
                        <td>
                            <a href="/setting-payment-type"><button class="text-warning">Edit</button></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection