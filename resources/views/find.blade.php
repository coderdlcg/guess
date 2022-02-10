<x-app-layout>
    <div class="home-page">
        <div class="block-game">
            <find-game-component :auth_user="{{$auth_user}}"></find-game-component>
        </div>
    </div>
</x-app-layout>
