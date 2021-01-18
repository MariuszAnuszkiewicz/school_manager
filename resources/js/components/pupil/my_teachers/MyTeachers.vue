<template>
    <div class="container">
        <div class="row justify-content-center">
            <div v-if="messagesWarning !== undefined" :style="{ display: showMessageWarning }" class="flex flash-container flash-style-warning">
                <div v-for="messageWarning in messagesWarning" class="error-explode">
                    <p>{{ messageWarning }}</p>
                </div>
            </div>
            <div v-if="messagesWarning[0] === undefined" class="col mt-5">
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
                        <tr v-for="(teacher, i) in teachers" :key="teacher.id">
                            <td class="text-center pt-3">{{ teacher.name }}</td>
                            <td class="text-center pt-3">{{ subjects[i] }}</td>
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
            messagesWarning: [],
            showMessageWarning: 'none',
            showModal: false,
        }
    },
    methods: {
        getSources() {
            axios.get('my-teachers').then(response => {
                this.teachers = response.data.teachers;
                this.subjects = response.data.subjects;
                this.showWarning(response.data.message);
            });
        },
        showWarning(warningText) {
            if (warningText !== undefined) {
                this.messagesWarning.push(warningText);
                this.messagesWarning.splice(1, this.messagesWarning.length);
                this.showMessageWarning = 'block';
            }
        },
        openModal() {
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
    .flash-style-warning {
        display: none;
        position: absolute;
        top: 250px;
        left: 42.2%;
        background-color: rgba(245, 34, 70, 0.3);
        width: 333px;
        height: 35px;
        text-align: center;
        border-radius: 7px;
    }

</style>
