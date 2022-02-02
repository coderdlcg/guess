<template>
    <div class="game-page row">
        <div class="left-player col-3"  :class="{active: isActivePlayer1}">
            <div class="player-name">
                <h2>{{ player1.name }}</h2>
            </div>
            <div class="number">
                {{ player1Number }}
            </div>
            <div class="footer">
                <div class="info">
                    <p>Ваш ход</p>
                    <a href="#" class="exit_game" data-toggle="modal" data-target="#staticBackdrop" @click="showModal">Покинуть игру</a>
                </div>
            </div>
        </div>
        <div class="game-info col-6">
            <div class="gameround p-3">
                <div class="table-round">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Раунд</th>
                                <th scope="col">Компьютер</th>
                                <th scope="col">{{ player1.name }}</th>
                                <th scope="col">{{ player2.name }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(round) in rounds">
                                <tr>
                                    <th scope="row">{{ round['round_id'] }}</th>

                                    <template v-if="round['guess_number'] > 0">
                                        <td>{{ round['guess_number'] }}</td>
                                    </template>
                                    <template v-else>
                                        <td>??</td>
                                    </template>

                                    <template v-if="round['player_1']">
                                        <td :class="{normal: round['winner'] === 8, success: round['winner'] === 1, failed: round['winner'] === 2}">{{ JSON.parse(round['player_1']).number }}</td>
                                    </template>
                                    <template v-else>
                                        <td>??</td>
                                    </template>

                                    <template v-if="round['player_2']">
                                        <td :class="{normal: round['winner'] === 8, failed: round['winner'] === 1, success: round['winner'] === 2}">{{ JSON.parse(round['player_2']).number }}</td>
                                    </template>
                                    <template v-else>
                                        <td>??</td>
                                    </template>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="messages" v-if="messages.length">
                    <div class="message" v-for="(message) in messages">
                        <span class="d-inline-block">{{ message }}</span>
                    </div>
                </div>

                <div class="footer">
                    <fieldset :disabled="disabled >= 1">
                        <div class="row">
                            <div class="col">
                                <input class="form-control input_number" min="1" max="20" type="number" :placeholder="placeholder"
                                       v-model.number="inputNumber"
                                       @keyup.enter="sendMessage">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mt20">
                                <button class="btn btn-lg btn-primary btn-block btn-send_number" @click="sendMessage">Угадать</button>
                            </div>
                        </div>
                    </fieldset>
                </div>
            </div>
        </div>
        <div class="right-player col-3" :class="{active: isActivePlayer2}">
            <div class="player-name" v-if="player2 !== null">
                <h2>{{ player2.name }}</h2>
            </div>
            <div class="number">
                {{ player2Number }}
            </div>
            <div class="footer">
                <div class="info">
                    Ход противника
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['game', 'user', 'player1', 'player2', 'first_move', 'rounds'],
    data() {
        return {
            messages: [],
            inputNumber: '',
            player1Number: '',
            player2Number: '',
            placeholder: 'Число от 1 до 20',
            disabled: 0,
            activeUsers: [],
            isActivePlayer1: false,
            isActivePlayer2: false
        }
    },
    computed: {
        channel() {
            return window.Echo.join('game.' + this.game.id);
        }
    },
    mounted() {
        if (this.player1.id === this.first_move) {
            this.isActivePlayer1 = true;
            this.disabled = 0;
        } else {
            this.isActivePlayer2 = true;
            this.disabled = 1;
        }

        let round = this.rounds.slice().pop();
        if (round && JSON.parse(round['player_1']).user_id === this.player1.id && round['guess_number'] === 0) {
            this.isActivePlayer1 = false;
            this.isActivePlayer2 = true;
            this.disabled = 1;
        }
        if (round && JSON.parse(round['player_1']).user_id === this.player2.id && round['guess_number'] === 0) {
            this.isActivePlayer1 = true;
            this.isActivePlayer2 = false;
            this.disabled = 0;
        }

        if (round && round['winner'] > 0 && round['round_id'] === 5) {
            // game over
            this.disabled = 1;
            this.isActivePlayer1 = false;
            this.isActivePlayer2 = false;
            this.placeholder = ''

            this.messages.push('Game Over!');
            // здесь активировать модальное окно
        }

        this.channel
            .here((users) => {
                this.activeUsers = users;
            })
            .joining((user) => {
                this.activeUsers.push(user);
            })
            .leaving((user) => {
                this.activeUsers.splice(this.activeUsers.indexOf(user), 1);
            })
            .listen('MessageSend', ({message, round}) => {
                
                if (round['winner'] > 0) {
                    this.rounds.pop();
                    this.rounds.push(round);
                    this.player1Number = '';
                    this.player2Number = '';
                } else {
                    this.rounds.push(round);
                }

                if (this.player2.id === message.user_id) {
                    this.player2Number = message.body;
                    this.player1Number = '??'; //
                    this.disabled = 0;
                    this.isActivePlayer1 = true;
                    this.isActivePlayer2 = false;
                } else {
                    this.player1Number = message.body;
                    this.player2Number = '??'; //
                    this.disabled = 1;
                    this.isActivePlayer1 = false;
                    this.isActivePlayer2 = true;
                }

                if (round['winner'] > 0 && round['round_id'] === 5) {
                    // game over
                    this.disabled = 1;
                    this.isActivePlayer1 = false;
                    this.isActivePlayer2 = false;

                    // здесь активировать модальное окно
                }

            })
    },
    methods: {
        sendMessage() {
            if (this.inputNumber !== null && this.inputNumber >= 1 && this.inputNumber <= 20) {
                axios.post('/messages', { body: this.inputNumber, user_id: this.user.id, game_id: this.game.id });
                this.player1Number = this.inputNumber;
                this.inputNumber = '';
            }
        },
        showModal() {
            // this.$refs['my-modal'].show()
        }
    }
}
</script>
