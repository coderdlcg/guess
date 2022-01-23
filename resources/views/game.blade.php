<x-app-layout>
    <div class="game-page row">
        <div class="left-player in_active col-3">
            <div class="player-name">
                <h2>Tom</h2>
            </div>
            <div class="number">
                13
            </div>
            <div class="footer">
                <div class="info">
                    <p>Ваш ход</p>
                    <a href="#" class="exit_game" data-toggle="modal" data-target="#staticBackdrop">Покинуть игру</a>
                </div>
            </div>
        </div>
        <div class="game-info col-6">
            <div id="app">
                <game-component></game-component>
            </div>
        </div>
        <div class="right-player col-3">
            <div class="player-name">
                <h2>Bob</h2>
            </div>
            <div class="number">
                13
            </div>
            <div class="footer">
                <div class="info">
                    Ход противника
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="text winner">Победа</div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('home') }}" class="btn btn-secondary btn-close">В главное меню</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
