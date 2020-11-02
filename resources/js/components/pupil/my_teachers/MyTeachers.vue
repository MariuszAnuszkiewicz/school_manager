<template>
    <div class="container">
        <div :style="switchFlashStyle" class="flex flash-container">
            <p>{{ message.text }}</p>
        </div>
        <div :style="{ display: this.showHide }" class="row justify-content-center">
            <div class="col-md-12 mt-5">
                <div class="card-body"><h5><strong class="header-text">My Teachers</strong></h5></div>
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center pt-2">Name</th>
                            <th class="text-center pt-2">Subject</th>
                            <th class="text-center pt-2">Phone</th>
                            <th class="text-center pt-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(teacher, index) in teachers" :key="teacher.id">
                            <td class="text-center pt-3">{{ teacher.name }}</td>
                            <td class="text-center pt-3">{{ subjects[index] }}</td>
                            <td class="text-center pt-3">{{ teacher.phone }}</td>
                            <td class="text-center">
                                <button id="send-email-modal" class="btn btn-success" @click="openModal()">
                                    <i class="fas fa-mail-bulk"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <my-teachers-modal :teachers="teachers" v-if="showModal === true">
            <h3 slot="header" class="modal-title">
                Send Message
            </h3>
            <div slot="body">

            </div>
            <div slot="footer">
                <button type="button" class="btn btn-outline-info" @click="closeModal()"> Close </button>
            </div>
        </my-teachers-modal>
    </div>
</template>

<script>
import MyTeachersModal from "../modals/MyTeachersModal";
export default {
    components: {
        MyTeachersModal
    },
    data() {
        return {
            teachers: {},
            subjects: {},
            showModal: false,
            switchFlashStyle: '',
            showHide: '',
            message: {
                text: 'There are no any My Teachers.',
            },
            flashStyle: {
                'display': 'none',
                show: {
                    'display': 'block',
                    'position': 'relative',
                    'top': '100px',
                    'left': '38.7%',
                    'background-color': 'rgba(245, 34, 70, 0.3)',
                    'width': '250px',
                    'height': '35px',
                    'text-align': 'center',
                    'border-radius': '7px',
                }
            },
        }
    },
    methods: {
        getTeachers() {
            axios.get('my-teachers').then(response => {
                this.teachers = response.data.teachers
                this.subjects = response.data.subjects
                this.showFlashMessage()
            });
        },
        showFlashMessage() {
            if (this.teachers.length < 1) {
                this.showHide = 'none'
                this.switchFlashStyle = this.flashStyle.show
            } else {
                this.switchFlashStyle = this.flashStyle
            }
        },
        openModal() {
            this.showModal = true;
            this.showHide = 'none';
        },
        closeModal() {
            this.showModal = false;
            setTimeout(() => {
                this.showHide = 'block';
            }, 500);
        },
    },
    mounted() {
        this.getTeachers()
    }
}
</script>

<style scoped>
    .container {
        display: block;
    }
    .header-text {
        color: #8f8f8f;
    }
    .flash-container {
        display: none;
    }
    .flash-container p {
        position: relative;
        top: 4px;
    }
</style>
