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
                <form @submit.prevent="submitForm()" method="POST" ref="classesForm" class="form-horizontal">
                    <div class="col-md-12 text-center">
                        <h6 class="header-text">Assign Class</h6>
                        <div class="select-container">
                            <select id="selectClass" class="show-menu-arrow">
                                <optgroup label="Select Class">
                                    <option class="overflow-auto" v-for="class_in_school in classes_in_school"
                                            :value="class_in_school.id">{{ class_in_school.name }}
                                    </option>
                                </optgroup>
                            </select>
                            <button class="btn btn-primary ml-2 mt-1 mb-2" type="submit">Submit</button>
                        </div>
                    </div>
                    <div class="col-md-12 mt-5">
                        <div class="card-body"><h5><strong class="header-text">List of Pupils</strong></h5></div>
                        <table class="table table-striped">
                            <thead class="bg-dark">
                                <tr>
                                    <th class="text-center text-white">Select Id</th>
                                    <th class="text-center text-white">Pupil Id</th>
                                    <th class="text-center text-white">User Id</th>
                                    <th class="text-center text-warning">Assign Class</th>
                                    <th class="text-center text-white">User Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <div class="custom-control custom-switch text-center pt-2 bg-warning">
                                    <label class="check-all-label"><strong>Select All</strong></label>
                                    <input @click="selectAll()" type="checkbox" id="select-all" class="select-all d-inline-block ml-2">
                                    <label class="check-sem1-label ml-4 mr-2"><strong>Semester 1</strong></label>
                                    <input type="radio" class="semester1-select"
                                           v-model="semesters"
                                           :value="1">
                                    <label class="check-sem2-label ml-4 mr-2"><strong>Semester 2</strong></label>
                                    <input type="radio" class="semester2-select"
                                           v-model="semesters"
                                           :value="2">
                                </div>
                                <tr v-for="(user, i) in users" :key="user.id">
                                    <td class="text-center">
                                        <input type="checkbox" class="pupil-select"
                                               v-model="selected"
                                               :value="pupils[i].id"
                                               @change="unSelectAll()">
                                    </td>
                                    <td class="text-center">{{ pupils[i].id }}</td>
                                    <td class="text-center">{{ user.id }}</td>
                                    <td class="text-center"><span class="text-danger"><b>{{ assign_classes.name }}</b></span></td>
                                    <td v-if="user.name.length > 35" class="text-center">{{ user.name.slice(0, 35) + ' ... '  }}</td>
                                    <td v-else class="text-center">{{ user.name }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
   </div>
</template>

<script>
export default {
    data() {
        return {
            users: {},
            pupils: {},
            classes_in_school: {},
            assign_classes: {},
            subject: {},
            selected: [],
            semesters: [],
            messagesInfo: [],
            messagesWarning: [],
            showMessageInfo: 'none',
            showMessageWarning: 'none',
            isSelected: false,
            message: {
               warningText: '',
               infoText: '',
            },
        }
    },
    methods: {
        getSources() {
            axios.get('pupils').then(response => {
                this.users = response.data.users;
                this.pupils = response.data.pupils;
                this.subject = response.data.subject;
                this.classes_in_school = response.data.classes_in_school;
                this.assign_classes = response.data.assign_classes;
                if (this.users === undefined) {
                    this.showWarning(response.data.message);
                }
            });
        },
        updatePupils(formData) {
            axios.post('update-pupils', formData).then(function (response) {
                this.showInfo();
            }).catch(function (error) {
                console.log(error.response.data)
            });
        },
        savePupilTeacher(formData) {
            if (this.selected.length > 0) {
                axios.post('save-pupil-teacher', formData).then(response => {

                }).catch(function (error) {
                    console.log(error.response.data);
                });
            }
        },
        savePupilSemester(formData) {
            if (this.selected.length > 0) {
                axios.post('save-pupil-semester', formData).then(response => {

                }).catch(function (error) {
                    console.log(error.response.data);
                });
            }
        },
        savePupilSubject() {
            if (this.selected.length > 0) {
                axios.post('save-pupil-subject', {
                    pupils: this.selected,
                    subject: this.subject,
                }).then(response => {
                    this.showInfo(response.data.message);
                }).catch(function (error) {
                    console.log(error.response.data);
                });
            }
        },
        submitForm() {
            if (this.selected.length > 0) {
                var form = this.$refs.classesForm;
                let formData = new FormData(form);
                formData.append('class_assign', document.querySelector('#selectClass').value);
                formData.append('pupils', this.selected);
                formData.append('semester', this.semesters);
                this.updatePupils(formData);
                this.savePupilTeacher(formData);
                this.savePupilSemester(formData);
                this.savePupilSubject();
            }
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
        showWarning(warningText) {
            if (this.messagesWarning !== null) {
                this.messagesWarning.push(warningText);
                this.messagesWarning.splice(1, this.messagesWarning.length);
                this.showMessageWarning = 'block';
            }
        },
        showInfo(alerts) {
            if (alerts) {
                var is = 'is';
            }
            switch (is) {
                case 'is':
                    this.messagesInfo.push(alerts);
                    this.showMessageInfo = 'block',
                    this.messagesInfo.splice(1, this.messagesInfo.length);
                    break;
                default:
                    this.messagesInfo.push(this.message.infoText = 'You are assign pupils to class.');
                    this.showMessageInfo = 'block',
                    this.messagesInfo.splice(1, this.messagesInfo.length);
                    break;
            }
        }
    },
    mounted() {
        this.getSources()
        this.submitForm()
    },
}
</script>

<style scoped>

    .header-text {
        color: #8f8f8f;
    }
    .select-all, .pupil-select, .semester1-select, .semester2-select {
        position: relative;
        top: 3px;
        left: 0px;
        width: 18px;
        height: 18px;
        background-color: #fffed5;
    }
    .flash-container p {
        position: relative;
        top: 4px;
    }
    .error-explode p {
        padding-top: 2px;
    }
    .flash-style-info {
        display: none;
        position: absolute;
        top: 255px;
        left: 41.7%;
        background-color: rgba(60, 204, 102, 0.3);
        width: 350px;
        height: 35px;
        text-align: center;
        border-radius: 7px;
    }
    .flash-style-warning {
        display: none;
        position: absolute;
        top: 250px;
        left: 41.5%;
        background-color: rgba(245, 34, 70, 0.3);
        width: 350px;
        height: 35px;
        text-align: center;
        border-radius: 7px;
    }
    #selectClass {
        margin-top: 10px;
        background-color: #F2F2F2;
        height: 37px;
    }

</style>
