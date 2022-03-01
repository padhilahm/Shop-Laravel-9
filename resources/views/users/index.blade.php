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
            User
        </div>
        <div class="panel-body">
            <a href="/users/create"><button class="text-danger">Add user</button></a>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col" style="width: 40">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        isset($_GET['page']) ? $i = ($_GET['page'] * $no) - $no : $i = 0;
                    @endphp
                    @foreach ($users as $user)
                    <tr>
                        <th scope="row">{{ ++$i }}</th>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><a href="/users/{{ $user->id }}/edit"><button class="text-warning">Edit</button></a> <form action="/users/{{ $user->id }}"
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
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

@endsection