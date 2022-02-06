<x-app-layout>
    <div class="home-page">
        <div class="block-game">
            <div class="h1">
                <h1>Угадай число</h1>
            </div>
            <find-game-component></find-game-component>
            <div class="list">
                <a href="{{ route('history') }}" class="btn btn-lg btn-primary btn-block btn-list" type="submit">Журнал матчей</a>
            </div>

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
