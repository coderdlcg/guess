<x-app-layout>
    <div class="history-page ">
        <div class="row">
            <div class="col-4 offset-4">
                <h1>История матчей</h1>
            </div>
        </div>
        <div class="history-games row">
            <div class="table-round offset-2 col-8">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">Дата</th>
                        <th scope="col">Соперник</th>
                        <th scope="col">Исход игры</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <th scope="row">10.11.2021 11:53</th>
                        <td>Bob</td>
                        <td class="failed">Поражение</td>
                    </tr>
                    <tr>
                        <th scope="row">12.11.2021 11:53</th>
                        <td>Jon</td>
                        <td class="success">Победа</td>
                    </tr>
                    <tr>
                        <th scope="row">13.11.2021 11:53</th>
                        <td>Tom</td>
                        <td class="normal">Ничья</td>
                    </tr>
                    </tbody>
                </table>
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
