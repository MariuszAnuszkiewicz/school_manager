<template>
    <div class="container">
        <div class="row justify-content-center">
            <div :style="switchFlashStyle" class="flex flash-container">
                <div v-if="alerts !== undefined" v-for="alert in alerts" class="error-explode">
                    <p>{{ alert }}</p>
                </div>
                <div v-if="messagesInfo !== undefined" v-for="messageInfo in messagesInfo" class="error-explode">
                    <p>{{ messageInfo }}</p>
                </div>
            </div>
            <div v-if="alerts[0] === undefined" class="col mt-5">
                <div class="card-body"><h5><strong class="header-text">My Messages</strong></h5></div>
                <table class="table table-striped">
                    <thead class="bg-dark">
                        <tr>
                            <th class="text-center text-white">Select Id</th>
                            <th class="text-center text-white">Teacher Name</th>
                            <th class="text-center text-white">Pupil Ids</th>
                            <th class="text-center text-white">My Messages</th>
                            <th class="text-center text-white">Date Send</th>
                            <th class="text-center text-white">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <div class="custom-control text-center pt-2 bg-warning">
                        <label class="check-all-label"><strong>Select All</strong></label>
                        <input @click="selectAll()" type="checkbox" id="select-all" class="select-all d-inline-block ml-2">
                    </div>
                    <div class="col-md-12  text-center d-inline-block pt-2 pb-2 bg-light">
                        <button class="btn btn-outline-danger" @click="deleteSelected()"><i class="fas fa-trash"></i></button>
                    </div>
                        <tr v-for="(myMessage, index) in myMessages.data" :key="myMessage.id">
                            <td class="text-center pt-3">
                              <input type="checkbox" class="messages-select"
                                     v-model="selected"
                                     :value="myMessage.id"
                                     @change="unSelectAll()">
                            </td>
                            <td class="text-center pt-3">{{ teacher }}</td>
                            <td class="text-center pt-3">{{ pupils[index] }}</td>
                            <td v-if="myMessage.message.length > 35" class="text-center pt-3">{{ myMessage.message.slice(0, 35) + ' ... ' }}</td>
                            <td v-else class="text-center pt-3">{{ myMessage.message }}</td>
                            <td class="text-center pt-3">{{ myMessage.created_at | formatDate(myMessage.created_at) }}</td>
                            <td class="text-center pt-2">
                               <button id="edit-message" class="btn btn-success" @click="openModal(myMessage.message, myMessage.id)">
                                  <i class="fas fa-edit"></i>
                               </button>
                               <a v-bind:href="'single-message/' + myMessage.id">
                                  <button class="btn btn-info"><i class="fas fa-eye"></i></button>
                               </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <pagination :data="myMessages" @pagination-change-page="getSources"></pagination>
            </div>
        </div>
        <edit-message :message_text="message_text" :message_id="message_id" v-if="showModal === true">
            <h3 slot="header" class="modal-title">
               Edit Message
            </h3>
            <div slot="body">

            </div>
            <div slot="footer">
               <button type="button" class="btn btn-outline-info" @click="closeModal">Close</button>
            </div>
        </edit-message>
    </div>
</template>

<script>
export default {
    data() {
        return {
            message_text: '',
            message_id: '',
            teacher: {},
            myMessages: {},
            pupils: {},
            alerts: [],
            messagesInfo: [],
            selected: [],
            isSelected: false,
            showModal: false,
            switchFlashStyle: '',
            message: {
                warningText: 'There are no any messages.',
                infoText: 'Message has been deleted',
            },
            flashStyleInfo: {
            'display': 'none',
                show: {
                   'display': 'block',
                   'position': 'absolute',
                   'top': '110px',
                   'left': '44.5%',
                   'background-color': 'rgba(60, 204, 102, 0.3)',
                   'width': '333px',
                   'height': '35px',
                   'text-align': 'center',
                   'border-radius': '7px',
                   'padding-bottom': '10px',
                }
            },
            flashStyleWarning: {
            'display': 'none',
                show: {
                   'display': 'block',
                   'position': 'absolute',
                   'top': '110px',
                   'left': '44.5%',
                   'background-color': 'rgba(245, 34, 70, 0.3)',
                   'width': '333px',
                   'height': '35px',
                   'text-align': 'center',
                   'border-radius': '7px',
                   'padding-bottom': '10px',
                }
            },
        }
    },
    methods: {
        getSources(page) {
            if (typeof page === 'undefined') {
                page = 1;
            }
            axios.get('my-messages?page=' + page).then(response => {
                this.myMessages = response.data.myMessages;
                this.teacher = response.data.teacher;
                this.pupils = response.data.pupils;
                this.showWarning();
            });
        },
        openModal(message, message_id) {
            this.message_text = message;
            this.message_id = message_id;
            this.showModal = true;
        },
        closeModal() {
            setTimeout(() => {
                this.showModal = false;
            }, 150);
        },
        selectAll() {
            this.isSelected = !this.isSelected;
            if (this.isSelected) {
                for(let item in this.myMessages.data){
                    this.selected.push(this.myMessages.data[item].id);
                }
            } else {
                this.selected.splice(0, this.selected.length);
            }
        },
        unSelectAll() {
            if (this.selected.length > 0) {
                this.isSelected = true;
            } else {
                this.isSelected = false;
            }
        },
        deleteSelected() {
            if (this.selected.length > 0) {
                axios.post('delete-messages', {selected: this.selected}).then(response => {
                    this.getSources();
                    this.showInfo();
                });
            }
        },
        showWarning() {
            if (this.myMessages === undefined) {
                this.alerts.push(this.message.warningText);
                this.switchFlashStyle = this.flashStyleWarning.show;
            }
        },
        showInfo() {
            if (this.myMessages.data.length > 0) {
                this.messagesInfo.push(this.message.infoText);
                this.switchFlashStyle = this.flashStyleInfo.show;
            }
        },
    },
    filters: {
        formatDate(value) {
            var date = new Date(value);
            let minutesFormat = '';
            if (date.getMinutes() < 10) {
                minutesFormat += "0" + date.getMinutes();
            } else {
                minutesFormat += date.getMinutes();
            }
            let timestamps = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getHours();
            return timestamps += " " + date.getHours() + ":" + minutesFormat
        }
    },
    mounted() {
        this.getSources()
    }
}
</script>

<style scoped>

    .header-text {
        color: #8f8f8f;
    }
    .select-all {
        position: relative;
        top: 3px;
        left: 0px;
        width: 18px;
        height: 18px;
        background-color: #fffed5;
    }
    .messages-select {
        position: relative;
        top: 3px;
        left: 0px;
        width: 18px;
        height: 18px;
        background-color: #fffed5;
    }
    .flash-container {
        display: none;
    }
    .flash-container p {
        position: relative;
        top: 4px;
    }
    .error-explode p {
        padding-top: 2px;
    }

</style>
