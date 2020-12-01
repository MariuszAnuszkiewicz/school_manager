<template>
    <div class="container">
        <div class="row justify-content-center">
            <div :style="switchFlashStyle" class="flex flash-container">
                <div v-if="errors.length > 0" v-for="error in errors" class="error-explode">
                   <p>{{ error }}</p>
                </div>
                <div v-else>
                   <p>{{ message_text }}</p>
                </div>
            </div>
            <div v-if="errors.length === 0" :style="{ display: 'block' }" class="col mt-5">
                <div class="card-body"><h5><strong class="header-text">My Messages</strong></h5></div>
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center pt-2">Select Id</th>
                            <th class="text-center pt-2">Teacher Name</th>
                            <th class="text-center pt-2">Pupil Id</th>
                            <th class="text-center pt-2">My Messages</th>
                            <th class="text-center pt-2">Date</th>
                            <th class="text-center pt-2">Actions</th>
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
                        <tr v-for="(my_message, index) in my_messages.data" :key="my_message.id">
                            <td class="text-center pt-3">
                              <input type="checkbox" class="messages-select"
                                     v-model="selected"
                                     :value="my_message.id"
                                     @change="unSelectAll()">
                            </td>
                            <td class="text-center pt-3">{{ teacher }}</td>
                            <td v-if="pupils.data[index][0]" class="text-center pt-3">{{ pupils.data[index][0].id }}</td>
                            <td v-else class="text-center pt-3">{{ 'empty' }}</td>
                            <td v-if="my_message.message.length > 35" class="text-center pt-3">{{ my_message.message.slice(0, 35) + ' ... ' }}</td>
                            <td v-else class="text-center pt-3">{{ my_message.message }}</td>
                            <td class="text-center pt-3">{{ my_message.created_at | formatDate(my_message.created_at) }}</td>
                            <td class="text-center pt-2">
                               <button id="edit-message" class="btn btn-success" @click="openModal(my_message.message, my_message.id)">
                                  <i class="fas fa-edit"></i>
                               </button>
                               <a v-bind:href="'single-message/' + my_message.id">
                                  <button class="btn btn-info"><i class="fas fa-eye"></i></button>
                               </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <pagination :data="my_messages" @pagination-change-page="getMyMessages"></pagination>
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
            my_messages: {},
            pupils: {},
            errors: [],
            selected: [],
            isSelected: false,
            showModal: false,
            switchFlashStyle: '',
            showHide: '',
            flashStyleInfo: {
             'display': 'none',
                show: {
                  'display': 'block',
                  'position': 'relative',
                  'top': '25px',
                  'left': '33%',
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
                  'position': 'relative',
                  'top': '25px',
                  'left': '0%',
                  'background-color': 'rgba(245, 34, 70, 0.3)',
                  'width': '333px',
                  'height': '35px',
                  'text-align': 'center',
                  'border-radius': '7px',
                  'padding-bottom': '10px',
                }
            },
            message: {
               text: '',
            },
        }
    },
    methods: {
        getMyMessages(page) {
            if (typeof page === 'undefined') {
                page = 1;
            }
            axios.get('my-messages?page=' + page).then(response => {
                if (response.data.my_messages !== undefined) {
                   this.my_messages = response.data.my_messages
                } else {
                  this.errors.push(response.data.message)
                    for (let i = 0; i < this.errors.length; i++) {
                        if (this.errors[i] !== undefined) {
                            this.switchFlashStyle = this.flashStyleWarning.show;
                        } else {
                            this.switchFlashStyle = this.flashStyleInfo;
                        }
                    }
                }
                this.teacher = response.data.teacher;
                this.pupils = response.data.pupils
            });
        },
        openModal(message, message_id) {
            this.message_text = message;
            this.message_id = message_id;
            this.showModal = true;
        },
        closeModal() {
            this.showModal = false;
            setTimeout(() => {
              this.showHide = 'block';
            }, 500);
        },
        selectAll() {
            this.isSelected = !this.isSelected;
            if (this.isSelected) {
                for(let item in this.my_messages.data){
                    this.selected.push(this.my_messages.data[item].id);
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
                   this.switchFlashStyle = this.flashStyleInfo.show;
                   this.message_text = response.data.message;
                });
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
        this.getMyMessages()
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

</style>