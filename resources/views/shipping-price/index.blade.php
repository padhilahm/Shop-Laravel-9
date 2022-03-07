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
            Shipping Price
        </div>
        <div class="panel-body">
            <a href="/shipping-price/create"><button class="text-danger">Add Shipping Price</button></a>
            <hr>
            <h4 id="editDisplay">Maksimal jarak untuk pengantaran adalah {{ $shippingMax }} KM <input type="submit" value="Edit" class="btn-primary" onclick="editShippingMax()"></h4>
            <form action="/update-shipping-max" method="POST" id="updateShippingMax" style="display: none">
                @csrf
                <input type="number" name="shippingMax" class="form-control" value="{{ $shippingMax }}" placeholder="Maksimal pengantaran" required>
                <input type="submit" value="Update" class="btn-primary">
                <input value="Cancel" class="btn-danger" onclick="cancelShippingMax()">
            </form>
            <hr>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Distince</th>
                        <th scope="col">Price</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0
                    @endphp
                    @foreach ($shippingPrices as $shippingPrice)
                    <tr>
                        @if ($i == ($shippingPrices->count() - 1))
                        <td>{{ $shippingPrice->distince }} KM - {{ $shippingMax }} KM</td>
                        @else
                        <td>{{ $shippingPrice->distince }} KM - {{ $shippingPrices[$i+1]->distince }} KM</td>
                        @endif

                        <td>Rp.{{ number_format($shippingPrice->price) }}</td>
                        <td><a href="/shipping-price/{{ $shippingPrice->id }}/edit"><button class="text-warning">Edit</button></a> <form action="/shipping-price/{{ $shippingPrice->id }}"
                                method="POST">
                                @csrf
                                @method('delete')
                                <button class="text-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @php
                        $i++
                    @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function editShippingMax() {
        $('#editDisplay').css('display','none');
        $('#updateShippingMax').css('display','block');
    }
    function cancelShippingMax() {
        $('#editDisplay').css('display','block');
        $('#updateShippingMax').css('display','none');
    }
</script>

@endsection