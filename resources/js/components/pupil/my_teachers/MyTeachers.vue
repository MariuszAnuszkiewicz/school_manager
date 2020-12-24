<template>
    <div class="container">
        <div class="row justify-content-center">
            <div :style="switchFlashStyle" class="flex flash-container">
                <div v-if="alerts !== undefined" v-for="alert in alerts" class="error-explode">
                    <p>{{ alert }}</p>
                </div>
            </div>
            <div v-if="alerts[0] === undefined" class="col mt-5">
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
                <button type="button" class="btn btn-outline-info" @click="closeModal()">Close</button>
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
            alerts: [],
            showModal: false,
            switchFlashStyle: '',
            showHide: '',
            message: {
                text: 'There are no any My Teachers.',
            },
            flashStyleWarning: {
                'display': 'none',
                show: {
                    'display': 'block',
                    'position': 'relative',
                    'top': '100px',
                    'left': '0%',
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
        getSources() {
            axios.get('my-teachers').then(response => {
                this.teachers = response.data.teachers
                this.subjects = response.data.subjects
                this.getWarning()
            });
        },
        getWarning() {
            if (this.teachers === undefined) {
                this.alerts.push(this.message.text);
                this.switchFlashStyle = this.flashStyleWarning.show;
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
        this.getSources()
    }
}
</script>

<style scoped>

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
