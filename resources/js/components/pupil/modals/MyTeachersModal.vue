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
                        <div :style="switchFlashStyle" class="flex flash-container">
                            <p class="pb-1">{{ message.text }}</p>
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
            success: '',
            switchFlashStyle: '',
            message: {
                text: 'Send message was successfully.',
            },
            flashStyleInfo: {
                'display': 'none',
                show: {
                    'display': 'block',
                    'position': 'relative',
                    'top': '10px',
                    'left': '65px',
                    'background-color': 'rgba(120, 208, 170, 0.3)',
                    'width': '250px',
                    'height': '35px',
                    'text-align': 'center',
                    'border-radius': '7px',
                    'margin-bottom': '25px',
                }
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
                }).catch((error) => {
                    this.success = false;
                });
                this.success = true;
                this.showFlashMessage();
                this.message_content = '';
            }
        },
        showFlashMessage() {
            if (this.success === true) {
                setTimeout(() => {
                    this.switchFlashStyle = this.flashStyleInfo.show
                }, 500);
            } else {
                this.switchFlashStyle = this.flashStyleInfo
            }
        },
    },
    mounted() {
        this.sendEmail()
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
       top: 4px;
   }
</style>
