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
            Category
        </div>
        <div class="panel-body">
            <a href="/categories/create"><button class="text-danger">Add Category</button></a>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" style="width: 40">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Slug</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        isset($_GET['page']) ? $i = ($_GET['page'] * $no) - $no : $i = 0;
                    @endphp
                    @foreach ($categories as $category)
                    <tr>
                        <th scope="row">{{ ++$i }}</th>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td><a href="/categories/{{ $category->id }}/edit"><button class="text-warning">Edit</button></a> <form action="/categories/{{ $category->id }}"
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
                {{ $categories->links() }}
            </div>
        </div>
    </div>
</div>

@endsection