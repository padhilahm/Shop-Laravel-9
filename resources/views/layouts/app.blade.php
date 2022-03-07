<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>TokoMe</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <link href="/css/styles.css" rel="stylesheet" />

    {{-- @if (Request::is('transaction-check') || (Request::is('cart') && session('cart') == null)) --}}
    @if ((Request::is('cart') && session('cart') == null))
    <style>
        #footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 2.5rem;
            /* Footer height */
        }
    </style>
    @endif
</head>

<body>
    @include('layouts.nav')

    @if (Request::is('') || Request::is('/') || Request::is('product'))
    <!-- Header-->
    @include('layouts.head')
    @endif

    <!-- Section-->

    <section class="py-5">
        @yield('container')

    </section>
    <!-- Footer-->
    <footer class="py-5 bg-dark" id="footer">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; TokoMe {{ date('Y') }}</p>
        </div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="/js/scripts.js"></script>
    @yield('scripts')
</body>

</html>