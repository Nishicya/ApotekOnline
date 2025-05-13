<nav id="navigation">
    <div class="container">
        <div id="responsive-nav">
            <ul class="main-nav nav navbar-nav">
                <li class="nav-item {{ request()->is('', 'guest') ? 'active show' : '' }}"><a href="{{ route('home') }}">Home</a></li>
                <li class="nav-item {{ request()->is('', 'guest') ? 'active show' : '' }}"><a href="{{ route('shop') }}">Shop</a></li>
                <li class="nav-item {{ request()->is('', 'guest') ? 'active show' : '' }}"><a href="#">About</a></li>
                <li class="nav-item {{ request()->is('', 'guest') ? 'active show' : '' }}"><a href="#">Contact Us</a></li>
            </ul>
        </div>
    </div>
</nav>
