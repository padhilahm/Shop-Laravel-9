@extends('layouts-admin.app')
<link rel="stylesheet" type="text/css" href="/css/trix.css">
	<script type="text/javascript" src="/js/trix.js"></script>

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
            Products
        </div>
        <div class="panel-body">
            {{-- {{ $product->category }}

            @foreach ($categories as $category)
            {{ $category->name }}
            @endforeach --}}

            <form action="/products/{{ $product->id }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('put')
                <input type="hidden" name="oldImage" value="{{ $product->image }}">
                <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="{{ old('name', $product->name) }}">
                        @error('name')
                            <small id="emailHelp" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputprice3" class="col-sm-2 col-form-label">Price</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="price" name="price" placeholder="Price" value="{{ old('price', $product->price) }}">
                        @error('price')
                            <small id="emailHelp" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputprice3" class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-10">
                        {{-- <textarea name="description" id="description" rows="6" class="form-control">{{ old('description', $product->description) }}</textarea> --}}
                        <input id="x" type="hidden" name="description" id="description" value="{{ old('description', $product->description) }}">
                        <trix-editor input="x"></trix-editor>
                        @error('description')
                            <small id="emailHelp" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputprice3" class="col-sm-2 col-form-label">Category</label>
                    <div class="col-sm-10">
                        <select name="category_id" id="category_id" class="form-control">
                            <option value="">
                                - Select Category -
                            </option>
                            @foreach ($categories as $category)
                            <option @if (old('category', $product->category_id) === $category->id)
                                selected
                            @endif value="{{ $category->id }}">
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <small id="emailHelp" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputprice3" class="col-sm-2 col-form-label">Image</label>
                    <div class="col-sm-10">
                        <input type="file" class="form-control" id="image" name="image" placeholder="Image" >
                        @error('image')
                            <small id="emailHelp" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                @if ($product->image)
                <div class="form-group row">
                    <label for="inputprice3" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <img src="/storage/{{ $product->image }}" alt="" style="width: 200">
                    </div>
                </div>
                    
                @endif
                <div class="form-group row">
                    <div class="col-sm-10">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection