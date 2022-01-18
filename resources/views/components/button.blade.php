<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn btn-lg btn-primary btn-block btn-list transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
