<x-app-layout>
    <div id="app">
        <game-component :game="{{$game}}" :first_move="{{$first_move}}" :player1="{{$player1}}" :player2="{{$player2}}" :user="{{Auth::user()}}" :rounds='@json($rounds)'></game-component>
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
