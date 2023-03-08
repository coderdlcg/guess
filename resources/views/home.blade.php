<x-app-layout>
    <div class="home-page">
        <div class="block-game">
            <div class="msg">
                @if ($message = Session::get('msg'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <strong>{{$message}}</strong>
                        <button type="button" class="close" data-bs-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif
            </div>
            <div class="h1">
                <h1>Угадай число</h1>
            </div>
            <div class="user-info">
                Игрок: <b>{{ $user->name }}</b>
            </div>
            <a href="{{ route('find') }}" class="btn btn-lg btn-primary btn-block btn-find" >Найти игру</a>
            <a href="{{ route('history') }}" class="btn btn-lg btn-primary btn-block btn-list" type="submit">Журнал матчей</a>
            <div class="exit">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a class="btn btn-lg btn-primary btn-block btn-list"
                       href="{{ route('logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();"
                    >
                        {{ __('Log Out') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
