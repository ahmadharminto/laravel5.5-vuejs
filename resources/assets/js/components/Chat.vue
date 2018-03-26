<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">Chat Room</div>
                    <div class="panel-body">
                        <div class="offset-4 col-md-4">
                            <li class="list-group-item active">
                                Chat Room <span class="badge badge-pill badge-danger">{{ numberOfUsers }}</span>
                                <a href="javascript:void(0);" class="btn btn-warning btn-xs" @click="clearChat">Clear</a>
                            </li>
                            <div class="badge badge-pill badge-primary">{{ typing }}</div>
                            <ul class="list-group" v-chat-scroll>
                                <list-message v-for="(msg,index) in chat.message" 
                                    v-bind:data="msg" 
                                    v-bind:key="msg.index" 
                                    v-bind:color=chat.color[index] 
                                    v-bind:user=chat.user[index]
                                    v-bind:time=chat.time[index]
                                >{{ msg }}</list-message>
                            </ul>
                            <input type="text" class="form-control" placeholder="Type your message..." v-model="message" @keyup.enter="send">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<style>
    .list-group {
        overflow-y: auto;
        height: 200px;
    }
</style>


<script>
    import Vue from 'vue';
    import VueChatScroll from 'vue-chat-scroll';
    import Toaster from 'v-toaster';
    import 'v-toaster/dist/v-toaster.css';
    import ListMessage from "./ListMessage.vue";

    Vue.use(VueChatScroll);
    Vue.use(Toaster, {
        timeout: 5000
    });

    export default {
        name: 'chat',
        components: {
            ListMessage
        },
        data()
        {
            return {
                message: '',
                chat: {
                    message: [],
                    user: [],
                    color: [],
                    time: []
                },
                typing: '',
                numberOfUsers: 0
            }
        },
        mounted()
        {
            this.getMessages();

            Echo.private('chat')
                .listen('ChatEvent', (e) => {
                    this.chat.message.push(e.message);
                    this.chat.color.push('warning');
                    this.chat.user.push(e.username);
                    this.chat.time.push(this.getTime());
                    this.saveMessage();
                })
                .listenForWhisper('typing', (e) => {
                    if (e.name != '') {
                        this.typing = 'typing...';
                    } else {
                        this.typing = '';
                    }
                });

            Echo.join('chat')
                .here((users) => {
                    this.numberOfUsers = users.length;
                })
                .joining((user) => {
                    this.numberOfUsers += 1;
                    this.$toaster.success(user.name + ' is joining the room')
                })
                .leaving((user) => {
                    this.numberOfUsers -= 1;
                    this.$toaster.warning(user.name + ' is leaving the room')
                });
        },
        watch: {
            message() {
                Echo.private('chat')
                    .whisper('typing', {
                        name: this.message
                    });
            }
        },
        methods: {
            send(){
                if (this.message.length != 0) {
                    this.chat.message.push(this.message);
                    this.chat.color.push('success');
                    this.chat.user.push('you');
                    this.chat.time.push(this.getTime());

                    axios.post('/data/send_chat', {
                        message: this.message,
                        chat: this.chat
                    })
                        .then(response => {
                            
                        })
                        .catch(error => {
                            console.log(error);
                        });

                    this.message = '';
                }
            },
            getTime(){
                let time = new Date();
                return time.getHours() + ':' + time.getMinutes();
            },
            getMessages(){
                axios.get('/data/chat_messages')
                    .then(response => {
                        if (!_.isEmpty(response.data)) {
                            this.chat = response.data;
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },
            saveMessage(){
                axios.post('/data/save_chat', {
                    chat: this.chat
                })
                    .then(response => {
                        
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }, 
            clearChat(){
                axios.post('/data/clear_chat')
                    .then(response => {
                        this.$toaster.success('Chat history cleared');
                        this.chat = {
                            message: [],
                            user: [],
                            color: [],
                            time: []
                        };
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        }
    }
</script>