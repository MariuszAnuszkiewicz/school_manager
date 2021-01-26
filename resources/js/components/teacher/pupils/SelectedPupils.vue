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
                            <button class="btn btn-outline-danger" @click="deleteMultipleTables()"><i class="fas fa-trash"></i></button>
                            <button id="send-multiple-message" class="btn btn-success" @click="openModal(selected)">
                               <i class="fas fa-envelope"></i>
                            </button>
                        </div>
                        <tr v-for="(user, i) in users" :key="user.id">
                            <td class="text-center pt-3">
                                <input type="checkbox" class="pupil-select"
                                       v-model="selected"
                                       :value="pupils[i].id"
                                       @change="unSelectAll()">
                            </td>
                            <td class="text-center pt-3">{{ pupils[i].id }}</td>
                            <td class="text-center pt-3">{{ user.id }}</td>
                            <td class="text-center pt-3">{{ assign_classes[i].name }}</td>
                            <td class="text-center pt-3">{{ user.name }}</td>
                            <td class="text-center">
                                <button id="send-message" class="btn btn-success" @click="openModal(pupils[i])">
                                    <i class="fas fa-envelope"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <send-message :selected="selected" :pupilData="pupilData" v-if="showModal === true">
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
            pupilData: undefined,
            isSelected: false,
            showModal: false,
            selected: [],
            messagesInfo: [],
            messagesWarning: [],
            showMessageInfo: 'none',
            showMessageWarning: 'none',
        }
    },
    methods: {
        getSources() {
            axios.get('selected-pupils').then(response => {
                this.pupils = response.data.pupils;
                this.assign_classes = response.data.assign_classes;
                if (response.data.users) {
                    this.users = response.data.users;
                } else {
                    this.showWarning(response.data.message);
                }
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
        deletePupilTeacher() {
            axios.post('delete-pupil-teacher', {selected: this.selected}).then(response => {
                this.getSources();
                this.showInfo(response.data.message);
            });
        },
        deletePupilSemester() {
            axios.post('delete-pupil-semester',
                {
                        selected: this.selected,
                        semesters: [1, 2]
                }
            ).then(response => {
                this.getSources();
                this.showInfo(response.data.message);
            });
        },
        deleteMultipleTables() {
            if (this.selected.length > 0) {
                this.deletePupilTeacher();
                this.deletePupilSemester();
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
        openModal(pupil = null) {
            if (pupil !== null) {
                this.pupilData = pupil;
            }
            this.showModal = true;
        },
        closeModal() {
            setTimeout(() => {
                this.showModal = false;
            }, 150);
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
        top: 120px;
        left: 42.1%;
        background-color: rgba(245, 34, 70, 0.3);
        width: 333px;
        height: 35px;
        text-align: center;
        border-radius: 7px;
    }

</style>
