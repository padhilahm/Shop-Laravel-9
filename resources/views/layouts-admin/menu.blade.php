<!-- Menu -->
<div class="side-menu">
    <nav class="navbar navbar-default" role="navigation">
        <!-- Main Menu -->
        <div class="side-menu-container">
            <ul class="nav navbar-nav">
                <li class="@if ($url === 'dashboard') active  @endif"><a href="/dashboard"><span class="glyphicon glyphicon-dashboard"></span>Dashboard</a></li>
                <li class="@if ($url === 'payments') active  @endif"><a href="/payments"><span class="glyphicon glyphicon-book"></span>Payment</a></li>
                <li class="@if ($url === 'products') active  @endif"><a href="/products"><span class="glyphicon glyphicon-list"></span>Products</a></li>
                <li class="@if ($url === 'categories') active  @endif"><a href="/categories"><span class="glyphicon glyphicon-align-justify"></span>Categories</a></li>
                <li class="@if ($url === 'buyers') active  @endif"><a href="/buyers"><span class="glyphicon glyphicon-user"></span>Buyers</a></li>
                <li class="@if ($url === 'users') active  @endif"><a href="/users"><span class="glyphicon glyphicon-user"></span>Users</a></li>
                <li class="@if ($url === 'shipping-price') active  @endif"><a href="/shipping-price"><span class="glyphicon glyphicon-cog"></span>Shipping Price</a></li>
                <li class="@if ($url === 'setting') active  @endif"><a href="/setting"><span class="glyphicon glyphicon-cog"></span>Settings</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>

</div>