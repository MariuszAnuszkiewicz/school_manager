<template>
    <div class="container">
        <div class="row justify-content-center">
            <div :style="switchFlashStyle" class="flex flash-container">
                <div v-if="alerts !== undefined" v-for="alert in alerts" class="error-explode">
                    <p>{{ alert }}</p>
                </div>
            </div>
            <div v-if="alerts[0] === undefined" :style="{ display: showHide }" class="col mt-5">
                <div class="card-body"><h5><strong class="header-text">Messages from Teachers</strong></h5></div>
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center pt-2">From</th>
                            <th class="text-center pt-2">Message</th>
                            <th class="text-center pt-2">Create Date</th>
                            <th class="text-center pt-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(message, index) in messages.data" :key="message.id">
                            <td class="text-center pt-3">{{ teachers[index] + ' (teacher)' }}</td>
                            <td class="text-center pt-3">{{ message.message.slice(0, 65) + ' ... ' }}</td>
                            <td class="text-center pt-3">{{ new Date(message.created_at).toLocaleString('pl-PL') }}</td>
                            <td class="text-center pt-3">
                                <a v-bind:href="'messages/' + message.id">
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
            alerts: [],
            switchFlashStyle: '',
            showHide: '',
            message: {
                text: 'There are no any messages.',
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
        getSources(page) {
            if (typeof page === 'undefined') {
                page = 1;
            }
            axios.get('messages?page=' + page).then(response => {
                this.messages = response.data.messages;
                this.teachers = response.data.teachers;
                this.getWarning();
            });
        },
        deleteMessage(message) {
            let _this = this;
            axios.delete('messages/' + message.id).then(response => {
                _this.getSources()
            });
        },
        getWarning() {
            if (this.messages === undefined) {
                this.alerts.push(this.message.text);
                this.switchFlashStyle = this.flashStyleWarning.show;
            }
        },
    },
    mounted() {
        this.getSources()
    },
}
</script>

<style scoped>
button {
    font-size: 12px;
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
