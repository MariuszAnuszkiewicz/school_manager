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
                <div class="card-body"><h5><strong class="header-text">Selected Pupils</strong></h5></div>
                <table class="table table-striped">
                    <thead class="bg-dark">
                        <tr>
                            <th class="text-center text-white">Select Id</th>
                            <th class="text-center text-white">Pupil Id</th>
                            <th class="text-center text-white">User Id</th>
                            <th class="text-center text-white">Assign Class</th>
                            <th class="text-center text-white">User Name</th>
                            <th class="text-center text-white">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <div class="custom-control text-center pt-2 bg-warning">
                            <label class="check-all-label"><strong>Select All</strong></label>
                            <input @click="selectAll()" type="checkbox" id="select-all" class="select-all ml-2">
                        </div>
                        <div class="col-md-12  text-center pt-2 pb-2 bg-light">
                            <button class="btn btn-outline-danger" @click="deleteSelected()"><i class="fas fa-trash"></i></button>
                            <button id="send-multiple-message" class="btn btn-success" @click="openModal()">
                               <i class="fas fa-envelope"></i>
                            </button>
                        </div>
                        <tr v-for="(user, index) in users" :key="user.id">
                            <td class="text-center pt-3">
                                <input type="checkbox" class="pupil-select"
                                       v-model="selected"
                                       :value="pupils[index].id"
                                       @change="unSelectAll()">
                            </td>
                            <td class="text-center pt-3">{{ pupils[index].id }}</td>
                            <td class="text-center pt-3">{{ user.id }}</td>
                            <td class="text-center pt-3">{{ assign_classes[index].name }}</td>
                            <td class="text-center pt-3">{{ user.name }}</td>
                            <td class="text-center">
                                <button id="send-message" class="btn btn-success" @click="openModal(pupils[index].id)">
                                    <i class="fas fa-envelope"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <send-message :selected="selected" :pupil_id="pupil_id" v-if="showModal === true">
            <h3 slot="header" class="modal-title">
                Send Message
            </h3>
            <div slot="footer">
               <button type="button" class="btn btn-outline-info" @click="closeModal">Close</button>
            </div>
        </send-message>
    </div>
</template>

<script>
export default {
    data() {
        return {
            users: {},
            pupils: {},
            assign_classes: {},
            selected: [],
            isSelected: false,
            showModal: false,
            switchFlashStyle: '',
            pupil_id: [],
            alerts: [],
            messagesInfo: [],
            message: {
                warningText: 'There are no any pupils.',
                infoText: 'Pupil has been deleted',
            },
            flashStyleWarning: {
              'display': 'none',
                show: {
                  'display': 'block',
                  'position': 'absolute',
                  'top': '110px',
                  'left': '44.5%',
                  'background-color': 'rgba(245, 34, 70, 0.3)',
                  'width': '250px',
                  'height': '35px',
                  'text-align': 'center',
                  'border-radius': '7px',
                }
            },
            flashStyleInfo: {
              'display': 'none',
                show: {
                  'display': 'block',
                  'position': 'absolute',
                  'top': '110px',
                  'left': '44.5%',
                  'background-color': 'rgba(60, 204, 102, 0.3)',
                  'width': '250px',
                  'height': '35px',
                  'text-align': 'center',
                  'border-radius': '7px',
                }
            },
        }
    },
    methods: {
        getSources() {
            axios.get('selected-pupils').then(response => {
                this.users = response.data.users;
                this.pupils = response.data.pupils;
                this.assign_classes = response.data.assign_classes;
                this.showWarning();
            });
        },
        selectAll() {
            this.isSelected = !this.isSelected;
            if (this.isSelected) {
                for (let i = 0; i < this.pupils.length; i++) {
                   this.selected.push(this.pupils[i].id);
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
            axios.post('delete-pupils', {selected: this.selected}).then(response => {
                this.getSources()
                if (this.selected.length > 0) {
                    this.messagesInfo.push(this.message.infoText);
                    this.switchFlashStyle = this.flashStyleInfo.show;
                }
                this.messagesInfo.splice(1, this.messagesInfo.length);
            });
        },
        showWarning() {
            if (this.users === undefined) {
                this.alerts.push(this.message.warningText);
                this.switchFlashStyle = this.flashStyleWarning.show;
            }
        },
        openModal(pupil = null) {
            this.showModal = true;
            this.pupil_id.push(pupil);
        },
        closeModal() {
            this.showModal = false;
            setTimeout(() => {
            }, 500);
        },
    },
    mounted() {
        this.getSources()
    },
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
    .pupil-select {
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
    #send-message {
       padding-top: 2px;
       margin-top: 2px;
       padding-bottom: 2px;
       margin-bottom: 2px;
    }
    .error-explode p {
        padding-top: 2px;
    }

</style>
