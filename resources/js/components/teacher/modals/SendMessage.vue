<template>
    <transition name="modal">
        <div class="overlay">
            <div class="sendMessage">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <slot name="header"></slot>
                        </div>
                        <form @submit.prevent="submitForm()" method="POST" ref="saveMessageForm" class="form-horizontal">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label id="label-message"><strong><p class="message-paragraph">Message:</p></strong>
                                       <textarea id="message" name="message" v-model="message" rows="5" cols="35"></textarea>
                                    </label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <slot name="footer"></slot>
                                <button type="submit" id="submit-btn" class="btn btn-primary">
                                   Send
                                </button>
                            </div>
                        </form>
                        <validate :inputs="[message, selected]" ref="validate"></validate>
                        <div v-if="messagesInfo !== undefined" :style="{ display: showMessageInfo }" class="flex flash-container flash-style-info">
                            <div v-for="messageInfo in messagesInfo" class="error-explode">
                               <p>{{ messageInfo }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
import Validate from "../../validation/Validate";
export default {
    components: {
        Validate
    },
    props: ['selected'],
    data() {
        return {
            message: '',
            confirm: false,
            messagesInfo: [],
            showMessageInfo: 'none',
        }
    },
    methods: {
        sendMessage(formData) {
            let _this = this;
            axios.post('send-message', formData).then(response => {
                this.showInfo(response.data.message);
            }).catch(function (error) {
                let submitBtn = document.getElementById('submit-btn');
                submitBtn.addEventListener('click', function () {
                    _this.removeError();
                });
                _this.validateInput(error.response);
            });
        },
        submitForm() {
            let form = this.$refs.saveMessageForm;
            let formData = new FormData(form);
            formData.append('selected', this.selected);
            formData.append('message', this.message);
            this.sendMessage(formData);
        },
        validateInput(errorsResponse) {
            this.$refs.validate.validateRun(errorsResponse);
        },
        removeError() {
            this.$refs.validate.removeErrorRun();
        },
        showInfo(infoText) {
            if (this.messagesInfo !== null) {
                this.messagesInfo.push(infoText);
                this.messagesInfo.splice(1, this.messagesInfo.length);
                this.showMessageInfo = 'block';
            }
        },
    },
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
    .sendMessage {
        display: table;
        position: relative;
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
        left: 50px;
        background-color: rgba(60, 204, 102, 0.3);
        width: 275px;
        height: 35px;
        text-align: center;
        border-radius: 7px;
        margin: 0px 0px 15px 0px;
    }

</style>
