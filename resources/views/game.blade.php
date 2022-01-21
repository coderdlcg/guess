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
            <div class="table-round">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">Ход</th>
                        <th scope="col">Компьютер</th>
                        <th scope="col">Том</th>
                        <th scope="col">Bob</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>12</td>
                        <td class="success">10</td>
                        <td class="failed">16</td>
                    </tr>
                    <tr>
                        <th scope="row">2</th>
                        <td>16</td>
                        <td class="normal">12</td>
                        <td class="normal">12</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="footer">
                <div class="row">
                    <div class="col">
                        <input class="form-control input_number" type="text" placeholder="Введите число" name="number">
                    </div>
                </div>
                <div class="row">
                    <div class="col mt20">
                        <button class="btn btn-lg btn-primary btn-block btn-send_number" name="send_number" type="submit">Угадать</button>
                    </div>
                </div>
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
