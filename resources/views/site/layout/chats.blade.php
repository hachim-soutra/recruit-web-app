<style>
    img {
        max-width: 100%;
    }

    .incoming_msg_img {
        display: inline-block;
        width: 6%;
    }

    .received_msg {
        display: inline-block;
        padding: 0 0 0 10px;
        vertical-align: top;
        width: 92%;
    }

    .received_withd_msg p {
        background: #ebebeb none repeat scroll 0 0;
        border-radius: 3px;
        color: #646464;
        font-size: 14px;
        margin: 0;
        padding: 5px 10px 5px 12px;
        width: 100%;
    }

    .time_date {
        color: #747474;
        display: block;
        font-size: 12px;
        margin: 8px 0 0;
        float: left;
    }

    .outgoing_msg .time_date {
        float: right;
    }

    .received_withd_msg {
        max-width: 57%;
    }

    .mesgs {
        float: left;
        padding: 30px 15px 0 25px;
        width: 60%;
    }

    .sent_msg p {
        background: #05728f none repeat scroll 0 0;
        border-radius: 3px;
        font-size: 14px;
        margin: 0;
        color: #fff;
        padding: 5px 10px 5px 12px;
        width: 100%;
    }

    .outgoing_msg {
        overflow: hidden;
        margin: 26px 0 26px;
    }

    .sent_msg {
        float: right;
        max-width: 46%;
    }

    .input_msg_write input {
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
        border: medium;
        color: #4c4c4c;
        font-size: 15px;
        min-height: 48px;
        width: 100%;
        outline: 0;
        border: none;
    }

    .type_msg {
        border: 1px solid #c4c4c4;
        position: relative;
        padding: 5px 1rem;
    }

    .msg_send_btn {
        background: #05728f none repeat scroll 0 0;
        border: medium none;
        border-radius: 50%;
        color: #fff;
        cursor: pointer;
        font-size: 17px;
        height: 33px;
        position: absolute;
        right: 10px;
        top: 11px;
        width: 33px;
    }

    .messaging {
        padding: 0 0 50px 0;
    }

    .msg_history {
        max-height: 516px;
        overflow-y: auto;
    }
</style>

<div class="mesgs w-100" id="app">
    <div class="msg_history w-100" ref="container">
        <div v-for="message in messages">
            <div v-if="message.user_id != {{ auth()->user()->id }}" class="incoming_msg">
                <div class="incoming_msg_img" v-if="avatar">
                    <img alt="avatar" :src="avatar" style="border-radius: 50%">
                </div>
                <div class="received_msg">
                    <div class="received_withd_msg">
                        <p>@{{ message.message }}</p>
                        <span class="time_date">@{{ message.created_at | carbonaze }}</span>
                    </div>
                </div>
            </div>
            <div v-else class="outgoing_msg">
                <div class="sent_msg">
                    <p>@{{ message.message }}</p>
                    <span class="time_date">@{{ message.created_at | carbonaze }}</span>
                </div>
            </div>
        </div>
    </div>
    <div class="type_msg">
        <div class="input_msg_write">
            <input type="text" class="write_msg" id="message" placeholder="Type a message" v-model="message"
                v-on:keyup.enter="sendMessage" />
            <button v-if="message" @click="sendMessage" class="msg_send_btn" type="button"><i
                    class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
<!-- Moment.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<!-- Moment.js Humanize Duration CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment-duration-format/2.3.2/moment-duration-format.min.js">
</script>

<script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
<script>
    new Vue({
        el: '#app',
        data: {
            messages: [],
            message: '',
            room: null,
            avatar: null,
        },
        filters: {
            carbonaze: function(value) {
                const myDate = moment(value, 'YYYY-MM-DDTHH:mm:ssZ').utc();
                return myDate.fromNow();
            }
        },
        created() {
            this.fetchMessages();
            let self = this;
            window.Echo.channel(`create.chat.{{ auth()->user()->id }}`)
                .listen('ChatCreated', (e) => {
                    console.log(e);
                    self.fetchMessages();
                });
        },
        methods: {

            scrollToBottom() {
                setTimeout(() => {
                    var container = this.$refs.container;
                    container.scrollTop = container.scrollHeight;
                }, 500);
            },
            sendMessage() {
                if (!this.message) {
                    return
                }
                let url = "{{ route('chat.store') }}";
                let self = this;
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        client_id: "{{ $clientId }}",
                        author_id: "{{ $authorId }}",
                        message: self.message,
                    },
                    success: function(res) {
                        self.message = "";
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            },
            async fetchMessages() {
                let self = this;
                let url = "{{ route('chat.index') }}";
                await $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        client_id: "{{ $clientId }}",
                        author_id: "{{ $authorId }}",
                    },
                    success: function(res) {
                        if (res.data) {
                            self.messages = JSON.parse(res.data.messages);
                            self.avatar = res.data.receiver.avatar ? res.data.receiver.avatar :
                                "{{ env('APP_URL') . '/' . 'uploads/noimg.jpg' }}";
                            window.Echo.channel(`chat.${res.data.id}`)
                                .listen('MessageSent', (e) => {
                                    self.fetchMessages();
                                });
                        }
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
                await this.scrollToBottom();
            }
        }
    });
</script>
