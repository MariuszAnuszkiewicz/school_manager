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
            <div class="col mt-5">
                <div class="card-body"><h5><strong class="header-text">Assign Subject for Teacher</strong></h5></div>
                <form @submit.prevent="submitForm()" method="POST" ref="subjectForm" class="form-horizontal">
                    <div class="select-container bg-warning text-center pt-4">
                        <div class="col-md-3 select-nested">
                            <select id="selectSubjects" class="show-menu-arrow bg-light">
                                <optgroup label="Select Subject">
                                    <option class="overflow-auto" v-for="subject in subjects"
                                            :value="subject.id">{{ subject.name }}
                                    </option>
                                </optgroup>
                            </select>
                            <button class="btn btn-warning ml-2 mt-1 mb-1" type="submit">Submit</button>
                        </div>
                    </div>
                    <table class="table table-striped">
                        <thead class="bg-dark">
                            <tr>
                                <th class="text-center text-white">Select Teacher</th>
                                <th class="text-center text-white">Teacher Id</th>
                                <th class="text-center text-white">Teacher Name</th>
                                <th class="text-center text-white">Subject Teacher's</th>
                            </tr>
                        </thead>
                        <tbody>
                            <div class="custom-control text-center pt-2 bg-warning">
                                <label class="check-all-label"><strong>Select All</strong></label>
                                <input @click="selectAll()" type="checkbox" class="select-all ml-2">
                            </div>
                            <tr v-for="(teacher, i) in teachers">
                                <td class="text-center pt-3">
                                    <input type="checkbox" class="teachers-select"
                                           v-model="selected"
                                           :value="teacher.id">
                                </td>
                                <td class="text-center pt-3">{{ teacher.id }}</td>
                                <td class="text-center pt-3">{{ users[i].name }}</td>
                                <td class="text-center pt-3">{{ subjectAssign[i].name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            teachers: {},
            subjectAssign : {},
            subjects: {},
            users: {},
            selected: [],
            isSelected: false,
            messagesInfo: [],
            messagesWarning: [],
            showMessageInfo: 'none',
            showMessageWarning: 'none',
        }
    },
    methods: {
        getSources() {
            axios.get('assign-subject-teacher').then(response => {
                this.teachers = response.data.teachers;
                this.subjects = response.data.subjects;
                this.subjectAssign = response.data.subjectAssign;
                this.users = response.data.users;
            });
        },
        saveSubjectTeacher(formData) {
            if (this.selected.length > 0) {
                axios.post('subject-assign', formData).then(response => {
                    this.showInfo(response.data.message);
                }).catch(function (error) {
                    console.log(error.response.data);
                });
            }
        },
        submitForm() {
            if (this.selected.length > 0) {
                var form = this.$refs.subjectForm;
                let formData = new FormData(form);
                formData.append('subject_assign', document.querySelector('#selectSubjects').value);
                formData.append('teacher', this.selected);
                this.saveSubjectTeacher(formData);
            }
        },
        selectAll() {
            this.isSelected = !this.isSelected;
            if (this.isSelected) {
                for (let i = 0; i < this.teachers.length; i++) {
                    this.selected.push(this.teachers[i].id);
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
        showInfo(infoText) {
            if (this.messagesInfo !== null) {
                this.messagesInfo.push(infoText);
                this.messagesInfo.splice(1, this.messagesInfo.length);
                this.showMessageInfo = 'block';
            }
        },
        showWarning(warningText) {
            if (warningText !== null) {
                this.messagesWarning.push(warningText);
                this.messagesWarning.splice(1, this.messagesWarning.length);
                this.showMessageWarning = 'block';
            }
        },
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
    .select-container {
        width: auto;
        hight: 350px;
        padding: 0px 0px 25px 0px;
    }
    .select-nested {
        position: relative;
        left: 37.6%;
        background-color: #685b4d;
        border-radius: 5px;
    }
    .teachers-select {
        position: relative;
        top: 3px;
        left: 0px;
        width: 18px;
        height: 18px;
        background-color: #fffed5;
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
    .error-explode p {
        padding-top: 5px;
    }

</style>