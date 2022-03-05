<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container px-4 px-lg-5">
        <a class="navbar-brand" href="/">Start Bootstrap</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span
                class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">

                <li class="nav-item"><a class="nav-link {{ Request::is('') ? 'active' : '' }} {{ Request::is('/') ? 'active' : '' }}" aria-current="page" href="/">Home</a></li>
                
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ Request::is('product*') ? 'active' : '' }}" id="navbarDropdown" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">Shop</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="/product">All Products</a></li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li><a class="dropdown-item" href="#!">Popular Items</a></li>
                        <li><a class="dropdown-item" href="#!">New Arrivals</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle {{ Request::is('transaction*') ? 'active' : '' }}" id="navbarDropdown" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">Transaction</a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="/transaction-check">Check Transaction</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link {{ Request::is('about*') ? 'active' : '' }}" href="#!">About</a></li>
            </ul>
            {{-- <form class="d-flex"> --}}
                <a href="/cart">
                    <button class="btn btn-outline-dark" type="submit">
                        <i class="bi-cart-fill me-1"></i>
                        Cart
                        <span class="badge bg-dark text-white ms-1 rounded-pill" id="totalCart">{{ count((array) session('cart'))
                            }}</span>
                    </button>
                </a>
                {{--
            </form> --}}
        </div>
    </div>
</nav>