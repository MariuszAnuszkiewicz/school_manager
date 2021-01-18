<template>
    <div class="container">
        <div class="row justify-content-center">
            <div v-if="messagesInfo !== undefined" :style="{ display: showMessageInfo }" class="flex flash-container flash-style-info">
                <div v-for="messageInfo in messagesInfo" class="error-explode">
                   <p>{{ messageInfo }}</p>
                </div>
            </div>
            <div v-if="messagesWarning !== undefined" :style="{ display: showMessageWarning }" class="flex flash-container flash-style-warning">
                <div v-for="messageWarning in messagesWarning" class="error-explode">
                   <p>{{ messageWarning }}</p>
                </div>
            </div>
            <div v-if="messagesWarning[0] === undefined" class="col mt-5">
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
                        <div class="col-md-12 text-center d-inline-block pt-2 pb-2 bg-light">
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
            myMessages: {},
            teacher: {},
            pupils: {},
            messagesInfo: [],
            messagesWarning: [],
            showMessageInfo: 'none',
            showMessageWarning: 'none',
            selected: [],
            isSelected: false,
            showModal: false,
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
                if (response.data.message) {
                    this.showWarning(response.data.message);
                }
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
                    this.showInfo('Message has been deleted');
                });
            }
        },
        showInfo(infoText) {
            if (this.messagesInfo !== null) {
                this.messagesInfo.push(infoText);
                this.messagesInfo.splice(1, this.messagesInfo.length);
                this.showMessageInfo = 'block';
            }
        },
        showWarning(warningText) {
            if (this.messagesWarning !== null) {
                this.messagesWarning.push(warningText);
                this.messagesWarning.splice(1, this.messagesWarning.length);
                this.showMessageWarning = 'block';
            }
        },
    },
    filters: {
        formatDate(value) {
            var date = new Date(value);
            let timestamps = '';
            let minutesFormat = '';

            if (date.getMinutes() < 10) {
                minutesFormat += "0" + date.getMinutes();
            } else {
                minutesFormat += date.getMinutes();
            }

            if (value === null) {
                return timestamps = "";
            } else {
                return timestamps +=
                    " " + date.getFullYear() +
                    "-" + (date.getMonth() + 1) +
                    "-" + date.getDate() +
                    " " + date.getHours() +
                    ":" + minutesFormat;
            }
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
    .flash-style-info {
        display: none;
        position: absolute;
        top: 120px;
        left: 42.1%;
        background-color: rgba(60, 204, 102, 0.3);
        width: 333px;
        height: 35px;
        text-align: center;
        border-radius: 7px;
    }
    .flash-style-warning {
        display: none;
        position: absolute;
        top: 250px;
        left: 42.1%;
        background-color: rgba(245, 34, 70, 0.3);
        width: 333px;
        height: 35px;
        text-align: center;
        border-radius: 7px;
    }
    .error-explode p {
        padding-top: 2px;
    }

</style>
