<template>
    <div>
        <form method="POST" action="/find_game">
            <input type="hidden" value="1" name="find">
            <input type="hidden" name="_token" :value="csrf">
            <button class="btn btn-lg btn-primary btn-block btn-find" @click="findGame">Найти игру</button>
        </form>

        <b-modal id="modalFindGame" centered no-close-on-backdrop no-close-on-esc hide-header-close hide-header hide-footer>
            <div class="modal-body">
                <div class="clock-loader"></div>
                <div class="text">Поиск соперника...</div>
            </div>
            <a href="#" class="exit_game" @click="cancelFindGame">Отмена</a>
        </b-modal>
    </div>
</template>

<script>
export default {
    props: [

    ],
    data() {
        return {
            csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        }
    },
    computed: {
        channel() {
            return window.Echo.join('find');
        }
    },
    mounted() {

        // this.channel
        //     .listen('FindGame', ({data}) => {
        //
        //     })
    },
    methods: {
        findGame() {
            // axios.post('/find', { body: 'find', user_id: this.user.id });
            this.showModal()
        },
        cancelFindGame() {
            // axios.post('/cancel', { body: 'cancel', user_id: this.user.id });
            this.hideModal()
        },
        hideModal() {
            this.$bvModal.hide('modalFindGame');
        },
        showModal() {
            this.$bvModal.show('modalFindGame');
        },
    }
}
</script>
