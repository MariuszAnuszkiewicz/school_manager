<template>
    <transition name="modal">
        <div class="overlay">
            <div class="myTeachersModal">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form method="POST" class="form-horizontal">
                            <div class="modal-header">
                                <slot name="header"></slot>
                            </div>
                            <div class="modal-body">
                                <textarea class="form-control" name="message_content" v-model="message_content" rows="5"></textarea>
                            </div>
                            <div class="modal-footer">
                                <slot name="footer"></slot>
                                <button type="button" class="btn btn-primary" @click.stop="sendEmail()">
                                    Submit
                                </button>
                            </div>
                        </form>
                       <div class="flash-wrapper">
                           <div v-if="this.confirm === true" class="flex flash-container flash-style-info">
                               <div v-for="messageInfo in messagesInfo" class="error-explode">
                                  <p>{{ messageInfo }}</p>
                               </div>
                           </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    props: ['teachers'],
    data() {
        return {
            message_content: '',
            confirm: false,
            messagesInfo: [],
            message: {
                infoText: 'Send message was successfully.',
            },
        }
    },
    methods: {
        sendEmail: function () {
            if (this.message_content !== '') {
                axios.post('send-email', {
                    email: this.teachers[0].email,
                    message: this.message_content,
                }).then(response => {
                    this.showInfo(this.message.infoText);
                }).catch((error) => {
                    console.log(error.response.data)
                });
            }
        },
        showInfo(infoText) {
            if (infoText !== null) {
                this.messagesInfo.push(infoText);
                this.messagesInfo.splice(1, this.messagesInfo.length);
                this.confirm = true;
            }
        },
    },
    mounted() {
        this.sendEmail();
    }
}
</script>

<style scoped>

    .overlay {
        position: absolute;
        top: 200px;
        width: 52%;
        height: 75%;
        background-color: rgba(0, 0, 0, 0.8);
        z-index: 5;
    }
   .myTeachersModal {
       display: table;
       position: absolute;
       top: 150px;
       left: 33%;
       width: 400px;
       height: 300px;
       background-color: #4c6fb1;
       z-index: 9999;
       padding: 10px 10px 10px 10px;
       transition: opacity .3s ease;
   }
   .flash-container {
       display: none;
   }
   .flash-container p {
       position: relative;
       top: 5px;
   }
   .flash-style-info {
       display: block;
       position: relative;
       top: 2px;
       left: 22px;
       background-color: rgba(60, 204, 102, 0.3);
       width: 333px;
       height: 35px;
       text-align: center;
       border-radius: 7px;
       margin: 0px 0px 15px 0px;
   }

</style>
