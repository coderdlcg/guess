<x-app-layout>
    <header>
        @include('layouts.navigation')
    </header>
    <div class="home-page">
        <div class="block-game">
            <div class="h1">
                <h1>Угадай число</h1>
            </div>
            <div class="find">
                <button class="btn btn-lg btn-primary btn-block btn-find" type="submit" data-toggle="modal" data-target="#staticBackdrop">Найти игру</button>
            </div>
            <div class="list">
                <a href="{{ route('history') }}" class="btn btn-lg btn-primary btn-block btn-list" type="submit">Журнал матчей</a>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="clock-loader"></div>
                        <div class="text">Поиск соперника...</div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">Отмена</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
