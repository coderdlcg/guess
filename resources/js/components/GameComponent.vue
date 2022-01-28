<template>
    <div class="game-page row">
        <div class="left-player in_active col-3">
            <div class="player-name">
                <h2>{{activeUsers[0]}}</h2>
            </div>
            <div class="number">
                13
            </div>
            <div class="footer">
                <div class="info">
                    <p>Ваш ход</p>
                    <a href="#" class="exit_game" data-toggle="modal" data-target="#staticBackdrop">Покинуть игру</a>
                </div>
            </div>
        </div>
        <div class="game-info col-6">
            <div class="gameround p-3">
                <div class="table-round">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Ход</th>
                            <th scope="col">Компьютер</th>
                            <th scope="col">Том</th>
                            <th scope="col">Bob</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>12</td>
                            <td class="success">10</td>
                            <td class="failed">16</td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>16</td>
                            <td class="normal">12</td>
                            <td class="normal">12</td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="messages" v-if="messages.length">
                    <div class="message" v-for="message in messages">
                        <span class="d-inline-block">{{ message }}</span>
                    </div>
                </div>

                <div class="footer">
                    <div class="row">
                        <div class="col">
                            <span v-if="isActive">{{isActive.name}} набирает сообщение...</span>
                            <input class="form-control input_number" type="text" placeholder="Введите число" v-model="textMessage" @keyup.enter="sendMessage" @keydown="actionUser">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mt20">
                            <button class="btn btn-lg btn-primary btn-block btn-send_number" @click="sendMessage">Угадать</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="right-player col-3">
            <div class="player-name" >
                <h2>{{activeUsers[1]}}</h2>
            </div>
            <div class="number">
                13
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
    props: ['game', 'user'],
    data() {
        return {
            messages: [],
            textMessage: '',
            isActive: false,
            typingTimer: false,
            activeUsers: []
        }
    },
    computed: {
        channel() {
            return window.Echo.join('game.' + this.game.id);
        }
    },
    mounted() {
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
            .listen('MessageSend', ({message}) => {
                this.messages.push(message.body);
                this.isActive = false;
            })
            .listenForWhisper('typing', (e) => {
                this.isActive = e;
                if(this.typingTimer) clearTimeout(this.typingTimer);
                this.typingTimer = setTimeout(() => {
                    this.isActive = false;
                }, 2000);
            });
    },
    methods: {
        sendMessage() {
            axios.post('/messages', { body: this.textMessage, game_id: this.game.id });
            this.messages.push(this.textMessage);
            this.textMessage = '';
        },
        actionUser() {
            this.channel
                .whisper('typing', {
                    name: this.user.name
                });
        }
    }
}
</script>
