<template>
    <transition name="modal">
        <div class="overlay">
            <div class="saveEvent">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <slot name="header"></slot>
                        </div>
                        <form @submit.prevent="submitForm()" method="POST" ref="saveEventForm" class="form-horizontal">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label id="label-message"><strong><p class="message-paragraph">Title:</p></strong>
                                        <textarea id="message" name="title" v-model="title" rows="5" cols="35"></textarea>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label id="label-event-start"><strong><p class="start-paragraph">Event Start:</p></strong>
                                        <select id="start-values" v-model="startModel">
                                            <option v-for="s in start" :value="s">{{ s }}</option>
                                        </select>
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label id="label-event-end"><strong><p class="start-paragraph">Event End:</p></strong>
                                        <select id="end-values" v-model="endModel">
                                            <option v-for="e in end" :value="e">{{ e }}</option>
                                        </select>
                                    </label>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <slot name="footer"></slot>
                                <button type="submit" id="send-email" class="btn btn-primary">
                                     Save Event
                                </button>
                            </div>
                        </form>
                        <validate :input="title" ref="validate"></validate>
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
    props: ['date', 'start', 'end', 'teacherId'],
    data() {
        return {
            title: '',
            startModel: '',
            endModel: '',
            confirm: false,
            messagesInfo: [],
            showMessageInfo: 'none',
        }
    },
    methods: {
        insertEvents(formData) {
            let _this = this;
            axios.post('save-events', formData).then(response => {
                _this.insertEventTeacher(response.data.message)
            }).catch(function (error) {
                _this.validateInput(error.response);
            });
            this.confirm = true;
        },
        insertEventTeacher(eventId) {
            axios.post('save-event-teacher',
                {
                    eventId: eventId,
                    teacherId: this.teacherId,
                }
            ).then(response => {
                this.showInfo(response.data.message);
            }).catch(function (error) {
                console.log(error.response.data)
            });
        },
        submitForm() {
            var form = this.$refs.saveEventForm;
            let formData = new FormData(form);
            let dataStart = '';
            let dataEnd = '';
            if (this.startModel.slice(0, 1) == 8 || this.startModel.slice(0, 1) == 9) {
                dataStart += '0' + this.startModel;
            } else {
                dataStart = this.startModel;
            }
            if (this.endModel.slice(0, 1) == 8 || this.endModel.slice(0, 1) == 9) {
                dataEnd += '0' + this.endModel;
            } else {
                dataEnd = this.endModel;
            }
            formData.append('title', this.title);
            formData.append('date', this.date);
            formData.append('start', this.date + ' ' + dataStart);
            formData.append('end', this.date + ' ' + dataEnd);
            this.insertEvents(formData);
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
        top: 75px;
        width: 52.5%;
        height: 175%;
        background-color: rgba(0, 0, 0, 0.8);
        z-index: 5;
    }
    .saveEvent {
        display: table;
        position: relative;
        top: 200px;
        left: 32%;
        width: 400px;
        height: 300px;
        background-color: #4c6fb1;
        z-index: 9999;
        padding: 10px 10px 10px 10px;
        transition: opacity .3s ease;
    }
    .flash-wrapper {
        margin-bottom: 15px;
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
