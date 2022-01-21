
<nav x-data class="navbar navbar-expand-lg navbar-light">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                @auth
                    <a class="nav-link user_name dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-expanded="false">{{ Auth::user()->name }}</a>
                @else
                    <a href="{{ route('login') }}" class="nav-link">Авторизация</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="nav-link">Регистрация</a>
                    @endif
                @endauth
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="{{ route('home') }}">Home</a>
                    <a class="dropdown-item" href="{{ route('game') }}">Game</a>
                    <div class="dropdown-divider"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                        this.closest('form').submit();">{{ __('Log Out') }}</a>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
