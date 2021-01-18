<template>
    <div class="container">
        <div class="row justify-content-center">
            <div v-if="messagesWarning !== undefined" :style="{ display: showMessageWarning }" class="flex flash-container flash-style-warning">
                <div v-for="messageWarning in messagesWarning" class="error-explode">
                    <p>{{ messageWarning }}</p>
                </div>
            </div>
            <div v-if="messagesWarning[0] === undefined" :style="{ display: showMessageWarning }" class="col mt-5">
                <div class="card-body"><h5><strong class="header-text">Messages from Teachers</strong></h5></div>
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center pt-2">From</th>
                            <th class="text-center pt-2">Message</th>
                            <th class="text-center pt-2">Date Create</th>
                            <th class="text-center pt-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(message, index) in messages.data" :key="message.id">
                            <td class="text-center pt-3">{{ teachers[index] + ' (teacher)' }}</td>
                            <td class="text-center pt-3">{{ message.message.slice(0, 65) + ' ... ' }}</td>
                            <td class="text-center pt-3">{{ new Date(message.created_at).toLocaleString('pl-PL') }}</td>
                            <td class="text-center">
                                <a v-bind:href="'/pupil/messages/' + message.id">
                                    <button class="btn btn-info"><i class="fas fa-eye"></i></button>
                                </a>
                                <button @click.prevent="deleteMessage(message)" class="btn btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <pagination :data="messages" @pagination-change-page="getSources"></pagination>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            messages: {},
            teachers: {},
            messagesWarning: [],
            showMessageWarning: 'none',
        }
    },

    methods: {
        getSources(page) {
            if (typeof page === 'undefined') {
                page = 1;
            }
            axios.get('messages?page=' + page).then(response => {
                this.messages = response.data.messages;
                this.teachers = response.data.teachers;
                this.showWarning(response.data.message);
            });
        },
        deleteMessage(message) {
            let _this = this;
            axios.delete('messages/' + message.id).then(response => {
                _this.getSources()
            });
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
    },
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
