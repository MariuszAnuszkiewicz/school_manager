<template>
    <div class="container">
        <div class="row justify-content-center">
            <div v-if="messagesWarning !== undefined" :style="{ display: showMessageWarning }" class="flex flash-container flash-style-warning">
                <div v-for="messageWarning in messagesWarning" class="error-explode">
                   <p>{{ messageWarning }}</p>
                </div>
            </div>
            <div v-if="messagesWarning[0] === undefined" :style="{ display: showMessageWarning }" class="col mt-5">
                <div class="card-body"><h5><strong class="header-text">Scheduling Tests</strong></h5></div>
                <table class="table table-striped">
                    <thead class="bg-dark">
                        <tr>
                            <th class="text-center text-white pt-2">Teacher</th>
                            <th class="text-center text-white pt-2">Title</th>
                            <th class="text-center text-white pt-2">Date</th>
                            <th class="text-center text-white pt-2">Start</th>
                            <th class="text-center text-white pt-2">End</th>
                            <th class="text-center text-white pt-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody >
                        <tr v-for="(event, index) in events.data" :key="event.id">
                            <td class="text-center pt-3">{{ teachers[index] }}</td>
                            <td class="text-center pt-3">{{ event.title }}</td>
                            <td class="text-center pt-3">{{ event.date }}</td>
                            <td class="text-center pt-3">{{ event.start }}</td>
                            <td class="text-center pt-3">{{ event.end }}</td>
                            <td class="text-center">
                                <button @click.prevent="deleteEvent(event)" class="btn btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <pagination :data="events" @pagination-change-page="getSources"></pagination>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            events: {},
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
            axios.get('events?page=' + page).then(response => {
                this.events = response.data.events;
                this.teachers = response.data.teachers;
                this.showWarning(response.data.message);
            });
        },
        deleteEvent(event) {
            let _this = this;
            axios.delete('events/' + event.id).then(response => {
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
    .error-explode p {
        padding-top: 3px;
    }

</style>
