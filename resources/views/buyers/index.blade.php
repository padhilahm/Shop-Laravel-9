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
            Buyers
        </div>
        <div class="panel-body">
            {{-- <a href="/users/create"><button class="text-danger">Add user</button></a> --}}
            <form action="/buyers" method="get">
                <div class="col-sm-4 text-right">
                    <input type="text" name="search" class="form-control" placeholder="Search...">
                </div>
                <div class="col-sm-1">
                    <button class="btn-primary">Search</button>
                </div>
            </form>
            <hr>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" style="width: 40">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        isset($_GET['page']) ? $i = ($_GET['page'] * $no) - $no : $i = 0;
                    @endphp
                    @foreach ($buyers as $buyer)
                    <tr>
                        <th scope="row">{{ ++$i }}</th>
                        <td>{{ $buyer->name }}</td>
                        <td>{{ $buyer->email }}</td>
                        <td>{{ $buyer->phone }}</td>
                        <td><a href="/buyers/{{ $buyer->id }}/edit"><button class="text-warning">Edit</button></a> <form action="/buyers/{{ $buyer->id }}"
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
                {{ $buyers->links() }}
            </div>
        </div>
    </div>
</div>

@endsection