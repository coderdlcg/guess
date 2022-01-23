<template>
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
                    <input class="form-control input_number" type="text" placeholder="Введите число" v-model="textMessage">
                </div>
            </div>
            <div class="row">
                <div class="col mt20">
                    <button class="btn btn-lg btn-primary btn-block btn-send_number" @click="sendMessage()">Угадать</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            textMessage: '',
            messages: [],
        }
    },
    created() {
        this.addMessage('You joined the game.');
        Echo.channel('game')
            .listen('MessageSend', (e) => {
                this.addMessage(e.message);
            });
    },
    methods: {
        addMessage(message) {
            // let date= new Date();
            // let timestamp = date.getHours() + ':' + date.getMinutes() + ':' + date.getSeconds();
            // this.messages.push(timestamp + ' ' + message);
            this.messages.push(message);
        },
        sendMessage() {
            axios.post('/api/message', {message: this.textMessage});
            this.textMessage = '';
        }
    }
}
</script>
