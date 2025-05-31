@extends('site.layout.app')
@section('title', 'Events')
@section('mystyle')
    <style>
        img {
            max-width: 100%;
        }

        .inbox_people {
            background: #f8f8f8 none repeat scroll 0 0;
            float: left;
            overflow: hidden;
            width: 40%;
            border-right: 1px solid #c4c4c4;
        }

        .inbox_msg {
            border: 1px solid #c4c4c4;
            clear: both;
            overflow: hidden;
        }

        .top_spac {
            margin: 20px 0 0;
        }


        .recent_heading {
            float: left;
            width: 40%;
        }

        .srch_bar {
            display: inline-block;
            text-align: right;
            width: 60%;
        }

        .headind_srch {
            padding: 10px 29px 10px 20px;
            overflow: hidden;
            border-bottom: 1px solid #c4c4c4;
        }

        .recent_heading h4 {
            color: var(--red-color);
            font-size: 21px;
            margin: auto;
        }

        .srch_bar input {
            border: 1px solid #cdcdcd;
            border-width: 0 0 1px 0;
            width: 80%;
            padding: 2px 0 4px 6px;
            background: none;
        }

        .srch_bar .input-group-addon button {
            background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
            border: medium none;
            padding: 0;
            color: #707070;
            font-size: 18px;
        }

        .srch_bar .input-group-addon {
            margin: 0 0 0 -27px;
        }

        .chat_ib h5 {
            font-size: 15px;
            color: #464646;
            margin: 0 0 8px 0;
        }

        .chat_ib h5 span {
            font-size: 13px;
            float: right;
        }

        .chat_ib p {
            font-size: 14px;
            color: #989898;
            margin: auto
        }

        .chat_img {
            float: left;
            width: 11%;
        }

        .chat_ib {
            float: left;
            padding: 0 0 0 15px;
            width: 88%;
        }

        .chat_people {
            overflow: hidden;
            clear: both;
        }

        .chat_list {
            border-bottom: 1px solid #c4c4c4;
            margin: 0;
            padding: 18px 16px 10px;
        }

        .inbox_chat {
            height: 550px;
            overflow-y: auto;
        }

        .active_chat {
            background: var(--red-color);
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
        }

        .received_withd_msg {
            width: 57%;
        }

        .mesgs {
            float: left;
            padding: 30px 15px 0 25px;
            width: 60%;
        }

        .sent_msg p {
            background: var(--red-color) none repeat scroll 0 0;
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
            width: 46%;
        }

        .input_msg_write input {
            background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
            border: medium none;
            color: #4c4c4c;
            font-size: 15px;
            min-height: 48px;
            width: 100%;
        }

        .type_msg {
            border-top: 1px solid #c4c4c4;
            position: relative;
        }

        .msg_send_btn {
            background: var(--red-color) none repeat scroll 0 0;
            border: medium none;
            border-radius: 50%;
            color: #fff;
            cursor: pointer;
            font-size: 17px;
            height: 33px;
            position: absolute;
            right: 0;
            top: 11px;
            width: 33px;
        }

        .messaging {
            padding: 0 0 50px 0;
        }

        .msg_history {
            height: 516px;
            overflow-y: auto;
        }

        #show {
            opacity: 0;
        }

        @media (max-width:480px) {
            .mesgs {
                padding: 0px;
                width: 60%;
            }

            .inbox_people {
                width: 100%;
            }
        }
    </style>
@endsection
@section('content')
    <div class="container my-5">
        <h3 class=" text-center">Messaging</h3>
        <div id="loader"></div>
        <div class="messaging"id="show">
            <div class="inbox_msg row m-0" id="app2">
                <div class="inbox_people col-md-4 p-0">
                    <div class="headind_srch">
                        <div class="recent_heading">
                            <h4>Recent</h4>
                        </div>
                        <div class="srch_bar">
                            <div class="stylish-input-group">
                                <input type="text" class="search-bar" v-model="search" placeholder="Search">
                                <span class="input-group-addon">
                                    <button type="button"> <i class="fa fa-search" aria-hidden="true"></i> </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="inbox_chat">
                        <div v-for="roomChat in searchWatch" class="chat_list cursor-pointer"
                            :class="{ 'active_chat text-white': roomChat.id == room.id }" @click="handelRoom(roomChat)">
                            <div class="chat_people">
                                <div class="chat_img">
                                    <img v-if="roomChat.receiver.avatar" alt="avatar" style="border-radius: 50%"
                                        loading="lazy" :src='roomChat.receiver.avatar'>
                                    <img v-else alt="avatar" style="border-radius: 50%" loading="lazy"
                                        src="{{ env('APP_URL') . '/' . 'uploads/noimg.jpg' }}">

                                </div>
                                <div class="chat_ib">
                                    <h5 :class="{ 'text-white': roomChat.id == room.id }">@{{ roomChat.receiver.name }}
                                        <span class="chat_date">
                                            @{{ roomChat.last_message?.created_at | carbonaze }}</span>
                                    </h5>
                                    <p :class="{ 'text-white': roomChat.id == room.id }">
                                        <i v-if="roomChat.last_message.user_id != user_id" class="fa fa-solid "
                                            :class="{
                                                'fa-check': !roomChat.last_message.seen && roomChat.id != room.id,
                                                'fa-check-circle': roomChat.last_message.seen || roomChat.id == room.id
                                            }"></i>

                                        @{{ roomChat.last_message.message }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="mesgs w-100" v-if="room">
                        <div class="msg_history w-100" ref="container">
                            <div v-for="message in messages">
                                <div v-if="message.user_id != {{ auth()->user()->id }}" class="incoming_msg">
                                    <div class="incoming_msg_img">
                                        <img v-if="room.receiver.avatar" alt="avatar" style="border-radius: 50%"
                                            loading="lazy" :src='room.receiver.avatar'>
                                        <img v-else alt="avatar" style="border-radius: 50%" loading="lazy"
                                            src="{{ env('APP_URL') . '/' . 'uploads/noimg.jpg' }}">
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
                                <input type="text" class="write_msg" id="message" placeholder="Type a message"
                                    v-model="message" v-on:keyup.enter="sendMessage" />
                                <button v-if="message" v-on:click="sendMessage" class="msg_send_btn" type="button"><i
                                        class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('myscript')
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <!-- Moment.js CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <!-- Moment.js Humanize Duration CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment-duration-format/2.3.2/moment-duration-format.min.js">
    </script>
    <script type="text/javascript" src="{{ asset('js/app.js') }}"></script>
    <script>
        new Vue({
            el: '#app2',
            data: {
                loading: false,
                rooms: [],
                messages: [],
                message: '',
                search: '',
                room: null,
                avatar: null,
                user_id: '{{ auth()->user()->id }}',
            },
            filters: {
                carbonaze: function(value) {
                    if (typeof value === "undefined") return "";
                    const myDate = moment(value, 'YYYY-MM-DDTHH:mm:ssZ').utc();
                    return myDate.fromNow();
                }
            },
            created() {
                let self = this;
                this.fetchData();

                window.Echo.channel(`create.chat.{{ auth()->user()->id }}`)
                    .listen('ChatCreated', (e) => {
                        self.fetchData(false);
                    });
                window.Echo.channel(`sent.chat.{{ auth()->user()->id }}`)
                    .listen('ChatSent', (e) => {
                        self.scrollToBottom();
                        self.fetchData(false);
                    });
                var loader = document.getElementById('loader');
                loader.style.display = "none";
                var element = document.getElementById('show');
                element.style.opacity = "1";
            },
            methods: {
                async fetchData(seen = true) {
                    let self = this;
                    let url = "{{ route('chat.api') }}";
                    await $.ajax({
                        url: url,
                        type: 'GET',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(res) {
                            if (res.rooms.length > 0) {
                                self.rooms = res.rooms;
                                if (!self.room || self.room.id === self.rooms[0].id) {
                                    self.handelRoom(self.rooms[0], seen);
                                }
                            }
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    });
                    this.loading = true;
                },
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
                            client_id: self.room.client_id,
                            author_id: self.room.author_id,
                            message: self.message,
                        },
                        success: function(res) {
                            let msg = {
                                "message": self.message,
                                "created_at": new Date(),
                                "user_id": self.user_id,
                                "seen": null
                            };
                            self.messages.push(msg);
                            self.fetchData(false);
                            self.message = "";
                        },
                        error: function(err) {
                            console.log(err);
                        }
                    });
                },
                async handelRoom(room, seen = true) {
                    this.room = room;
                    this.messages = await JSON.parse(this.room.messages);
                    this.room.last_message.seen = true;
                    await this.scrollToBottom();

                },
                async fetchMessages() {
                    let self = this;
                    let url = "{{ route('chat.index') }}";
                    await $.ajax({
                        url: url,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            client_id: room.client_id,
                            author_id: room.author_id,
                        },
                        success: function(res) {
                            if (res.data) {
                                self.handelRoom(res.data);
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
                },
            },
            computed: {
                searchWatch() {
                    return this.rooms.filter(w => w.receiver.name.toLowerCase().includes(this.search.toLowerCase()))
                }
            }
        });
    </script>
@endsection
