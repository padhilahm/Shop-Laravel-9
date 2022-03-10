@extends('layouts.app')

@section('container')
 
<div class="container px-4 px-lg-5">
   
    <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
        @foreach ($categories as $category)
        <div class="col mb-5">
            <div class="card h-100">
                <!-- category image-->
                @if ($category->image)
                <img class="card-img-top" src="/storage/{{ $category->image }}" alt="..." />
                @else
                <img class="card-img-top" src="https://dummyimage.com/450x300/dee2e6/6c757d.jpg" alt="..." />
                @endif
                
                <!-- category details-->
                <div class="card-body p-4">
                    <div class="text-center">
                        <!-- category name-->
                        <h5 class="fw-bolder">{{ $category->name }}</h5>
                        <!-- category price-->
                    </div>
                </div>
                <!-- category actions-->
                <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                    <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="category/{{ $category->slug }}">Lihat Product</a></div>
                </div>
            </div>
        </div>
            
        @endforeach

    </div>
</div>
@endsection