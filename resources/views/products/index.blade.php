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
            Products
        </div>
        <div class="panel-body">
            <a href="/products/create"><button class="text-danger">Add Product</button></a>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Price</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Category</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        isset($_GET['page']) ? $i = ($_GET['page'] * $no) - $no : $i = 0;
                    @endphp
                    @foreach ($products as $product)
                    <tr>
                        <th scope="row">{{ ++$i }}</th>
                        <td>{{ $product->name }}</td>
                        <td>Rp.{{ number_format($product->price) }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->category }}</td>
                        <td><a href="/products/{{ $product->id }}/edit"><button class="text-warning">Edit</button></a> <form action="/products/{{ $product->id }}"
                                method="POST">
                                @csrf
                                @method('delete')
                                <button class="text-danger" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="text-right">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>

@endsection