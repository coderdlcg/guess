<template>
    <div>
        <b-modal id="modalFindGame" centered no-close-on-backdrop no-close-on-esc hide-header-close hide-header hide-footer>
            <div class="modal-body">
                <div class="clock-loader"></div>
                <div class="text">Поиск соперника...</div>
            </div>
            <a href="/" class="exit_game">Отмена</a>
        </b-modal>
    </div>
</template>

<script>
export default {
    props: [
        'user'
    ],
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    },
    mounted() {
        this.showModal();
        Echo.channel('find')
            .listen('StartGame', (data) => {
                window.location.href = '/game/' + data.message.game_id;
            })
            .listen('FindGame', (data) => {
                if (data.message.body === 'find' && this.user.id !== data.message.user.id) {
                    this.createGame([this.user, data.message.user]);
                }
            });
        this.findGame(this.user);
    },
    methods: {
        findGame(user) {
            axios.post('/find_game', { body: 'find', user: user });
        },
        createGame(users) {
            axios.post('/new_game', { body: 'create', users: users });
        },
        showModal() {
            this.$bvModal.show('modalFindGame');
        },
    }
}
</script>
