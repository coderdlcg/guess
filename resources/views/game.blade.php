<x-app-layout>
    <game-component :game="{{$game}}"
                    :first_move="{{$first_move}}"
                    :left_player="{{$left_player}}"
                    :right_player="{{$right_player}}"
                    :player_1="{{$player_1}}"
                    :player_2="{{$player_2}}"
                    :user="{{Auth::user()}}"
                    :rounds='@json($rounds)'
                    :winner="{{$winner}}"></game-component>
</x-app-layout>
