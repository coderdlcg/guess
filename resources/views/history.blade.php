<x-app-layout>
    <div class="history-page ">
        <div class="row">
            <div class="col-12">
                <h1>История матчей</h1>
            </div>
        </div>
        <div class="user-info row">
            <div class="offset-1 col-10">
                Игрок: <b>{{ $user->name }}</b>
            </div>
        </div>
        <div class="history-games row">
            <div class="table-round offset-1 col-10">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Дата</th>
                        <th scope="col">Соперник</th>
                        <th scope="col">Исход игры</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($games as $game)
                        <tr>
                            <th scope="row">{{ \Carbon\Carbon::createFromDate($game->date)->format('d.m.Y H:i') }}</th>
                            <th scope="row">{{ $game->opponent_name }}</th>

                            @if($game->winner === \App\Models\Game::ROLES['none'])
                                <td class="normal">Ничья</td>
                            @elseif($game->winner === $game->role)
                                <td class="failed">Поражение</td>
                            @else
                                <td class="success">Победа</td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <div class="paginate">
                    {{ $games->links() }}
                </div>
            </div>
        </div>
        <div class="footer">
            <div class="row">
                <div class="col-4 offset-4 mt20">
                    <a href="{{ route('home') }}" class="btn btn-lg btn-primary btn-block btn-send_number" name="send_number" type="submit">Назад</a>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
