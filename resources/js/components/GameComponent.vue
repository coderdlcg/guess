<template>
    <div class="game-page row">
        <div class="left-player col-3"  :class="{active: isActiveLeftPlayer}">
            <div class="player-name">
                <h2>{{ left_player.name }}</h2>
            </div>
            <div class="number">
                {{ leftPlayerNumber }}
            </div>
            <div class="footer">
                <div class="info">
                    <p>Ваш ход</p>
                    <a href="#" class="exit_game" @click="leaveGame">Покинуть игру</a>
                </div>
            </div>
        </div>
        <div class="game-info col-6" :class="{game_over: isGameOver}">
            <div class="gameround p-3">
                <div class="table-round">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Раунд</th>
                                <th scope="col">Компьютер</th>
                                <th scope="col">{{ player_1.name }}</th>
                                <th scope="col">{{ player_2.name }}</th>
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
                                        <td>-</td>
                                    </template>

                                    <template v-if="round['player_1']">
                                        <td :class="{normal: round['winner'] === 8, success: round['winner'] === 1, failed: round['winner'] === 2}">
                                            {{ JSON.parse(round['player_1']).number }}
                                        </td>
                                    </template>
                                    <template v-else>
                                        <td>-</td>
                                    </template>

                                    <template v-if="round['player_2']">
                                        <td :class="{normal: round['winner'] === 8, failed: round['winner'] === 1, success: round['winner'] === 2}">
                                            {{ JSON.parse(round['player_2']).number }}
                                        </td>
                                    </template>
                                    <template v-else>
                                        <td>-</td>
                                    </template>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
                <div class="footer">
                    <div class="timer" :class="{inactive: isActiveRightPlayer}">
                        <span class="time">{{ currentTime }}</span>
                    </div>
                    <fieldset :disabled="disabled >= 1">
                        <div class="row">
                            <div class="col">
                                <input class="form-control input_number" min="1" max="20" type="number" autofocus :placeholder="placeholder"
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
        <div class="right-player col-3" :class="{active: isActiveRightPlayer}">
            <div class="player-name" v-if="right_player !== null">
                <h2>{{ right_player.name }}</h2>
            </div>
            <div class="number">
                {{ rightPlayerNumber }}
            </div>
            <div class="footer">
                <div class="info">
                    Ход противника
                </div>
            </div>
        </div>
        <b-modal id="modalLeaveGame" centered no-close-on-backdrop no-close-on-esc hide-header-close hide-header hide-footer>
            <div class="text winner">{{ modal_text }}</div>
            <a href="/" class="btn btn-tohome">В главное меню</a>
        </b-modal>
    </div>
</template>

<script>
export default {
    props: [
        'game',
        'user',
        'left_player',
        'right_player',
        'player_1',
        'player_2',
        'rounds',
        'winner'
    ],
    data() {
        return {
            message: '',
            inputNumber: '',
            leftPlayerNumber: '',
            rightPlayerNumber: '',
            placeholder: 'Число от 1 до 20',
            disabled: 0,
            activeUsers: [],
            isActiveLeftPlayer: false,
            isActiveRightPlayer: false,
            isGameOver: false,
            modal_text: '',
            limitTime: 20,
            currentTime: null,
            timer: null,
        }
    },
    computed: {
        channel() {
            return window.Echo.join('game.' + this.game.id);
        }
    },
    mounted() {
        if (this.left_player.id === this.player_1.id) {
            this.togglePLayer('left');
        } else {
            this.togglePLayer('right');
        }

        let round = this.rounds.slice().pop();

        if (round
            && JSON.parse(round['player_1']).user_id === this.left_player.id
            && JSON.parse(round['player_1']).number !== 'x')
        {
            this.togglePLayer('right');
        }

        if (round
            && JSON.parse(round['player_1']).user_id === this.right_player.id
            && JSON.parse(round['player_1']).number !== 'x')
        {
            this.togglePLayer('left');
        }

        if (this.winner) {
            this.gameOver(this.winner);
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
                    if (message.body !== 'x') {
                        this.rounds.pop();
                    }

                    if (message.body === 'x' && message.user_id === this.player_2.id) {
                        this.rounds.pop();
                    }

                    this.rounds.push(round);
                    this.leftPlayerNumber = '';
                    this.rightPlayerNumber = '';
                } else {
                    this.rightPlayerNumber = '';
                    this.rounds.push(round);
                }

                if (this.right_player.id === message.user_id && message.body !== 'x') {
                    this.rightPlayerNumber = message.body;
                    this.togglePLayer('left');
                }

                if (this.left_player.id === message.user_id && message.body !== 'x') {
                    this.leftPlayerNumber = message.body;
                    this.togglePLayer('right');
                }

                if (message.body === 'x') {
                    if (this.left_player.id === this.player_2.id) {
                        this.togglePLayer('right');
                    } else {
                        this.togglePLayer('left');
                    }
                }
            })
            .listen('GameOver', ({data}) => {
                if (data.winner) {
                    this.gameOver(data.winner);
                }
            })
    },
    destroyed() {
        this.stopTimer()
    },
    methods: {
        startTimer() {
            this.currentTime = this.limitTime;
            this.timer = setInterval(() => {
                this.currentTime--
            }, 1000)
        },
        stopTimer() {
            clearTimeout(this.timer)
        },
        togglePLayer(player) {
            if (player === 'left') {
                this.leftPlayerNumber = '?';
                this.disabled = 0;
                this.isActiveLeftPlayer = true;
                this.isActiveRightPlayer = false;
            }

            if (player === 'right') {
                this.rightPlayerNumber = '?';
                this.disabled = 1;
                this.isActiveLeftPlayer = false;
                this.isActiveRightPlayer = true;
            }

            if (player === 'none') {
                this.disabled = 1;
                this.isActiveLeftPlayer = false;
                this.isActiveRightPlayer = false;
                this.placeholder = '';
                this.leftPlayerNumber = '';
                this.rightPlayerNumber = '';
            }

            if (this.disabled === 0) {
                this.startTimer()
            }
        },
        sendMessage() {
            if (this.inputNumber !== null && this.inputNumber >= 1 && this.inputNumber <= 20) {
                this.stopTimer();
                axios.post('/processing', { body: this.inputNumber, user_id: this.user.id, game_id: this.game.id });
                this.leftPlayerNumber = this.inputNumber;
                this.inputNumber = '';
            }
        },
        gameOver(winner) {
            this.togglePLayer('none');
            this.isGameOver = true;

            let text = '';
            if (winner !== 8) {
                if (this.left_player.id === winner.id) {
                    text = 'Победа!';
                } else {
                    text = 'Проигрыш!';
                }
            } else {
                text = 'Ничья!';
            }
            this.message = text;
            this.modal_text = text;
            this.showModal()
        },
        leaveGame() {
            axios.post('/processing', { body: 'leave', user_id: this.user.id, game_id: this.game.id });
            this.showModal()
        },
        showModal() {
            this.$bvModal.show('modalLeaveGame');
        },
    },
    watch: {
        currentTime(time) {
            if (time === 0) {
                this.stopTimer();

                if (!this.isGameOver)  {
                    axios.post('/processing', { body: 'x', user_id: this.user.id, game_id: this.game.id });
                }
            }
        }
    },
}
</script>
