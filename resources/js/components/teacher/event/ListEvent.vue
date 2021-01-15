<template>
    <div class="container">
        <div class="row justify-content-center">
            <div v-if="messagesWarning !== undefined" :style="{ display: showMessageWarning }" class="flex flash-container flash-style-warning">
                <div v-for="messageWarning in messagesWarning" class="error-explode">
                   <p>{{ messageWarning }}</p>
                </div>
            </div>
            <div v-if="messagesWarning[0] === undefined" class="col mt-5">
                <div class="card-body"><h5><strong class="header-text">Events List</strong></h5></div>
                <table class="table table-striped">
                    <thead class="bg-dark">
                        <tr>
                            <th class="text-center text-white">Event Id</th>
                            <th class="text-center text-white">Title</th>
                            <th class="text-center text-white">Date</th>
                            <th class="text-center text-white">Start</th>
                            <th class="text-center text-white">End</th>
                            <th class="text-center text-white">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="event in events.data">
                            <td class="text-center pt-3">{{ event.id }}</td>
                            <td v-if="event.title.length > 35" class="text-center pt-3">{{ event.title.splice(0, 35) + ' ... ' }}</td>
                            <td v-else class="text-center pt-3">{{ event.title }}</td>
                            <td class="text-center pt-3">{{ event.date }}</td>
                            <td class="text-center pt-3">{{ event.start | HoursDate(event.start) }}</td>
                            <td class="text-center pt-3">{{ event.end | HoursDate(event.end) }}</td>
                            <td class="text-center">
                                <button id="edit-event" class="btn btn-success" @click="openModalEdit(event)">
                                   <i class="fas fa-edit"></i>
                                </button>
                                <button id="delete-event" class="btn btn-danger" @click.prevent="deleteEvent(event)">
                                   <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <pagination :data="events" @pagination-change-page="getSources"></pagination>
            </div>
        </div>
        <edit-event v-if="showModalEdit === true" :eventData="eventData">
            <h3 slot="header" class="modal-title">
                Edit Event
            </h3>
            <div slot="body">

            </div>
            <div slot="footer">
                <button type="button" class="btn btn-outline-info" @click="closeModalEdit">Close</button>
            </div>
        </edit-event>
    </div>
</template>

<script>
export default {
    data() {
        return {
            eventData: undefined,
            events: {},
            showModalEdit: false,
            messagesWarning: [],
            showMessageWarning: 'none',
            message: {
                warningText: '',
            },
        }
    },
    methods: {
        getSources(page) {
            if (typeof page === 'undefined') {
              page = 1;
            }
            axios.get('list-events?page=' + page).then(response => {
                this.events = response.data.events;
                this.showWarning(response.data.message);
            });
        },
        deleteEvent(event) {
            let _this = this;
            axios.delete('delete-event/' + event.id).then(response => {
                _this.getSources();
            });
        },
        openModalEdit(event) {
            this.eventData = event;
            this.showModalEdit = true;
        },
        closeModalEdit() {
            setTimeout(() => {
                this.showModalEdit = false;
            }, 150);
        },
        showWarning(warningText) {
            if (this.events === undefined) {
                this.messagesWarning.push(warningText);
                this.messagesWarning.splice(1, this.messagesWarning.length);
                this.showMessageWarning = 'block';
            }
        },
    },
    filters: {
        HoursDate(value) {
            var date = new Date(value);
            let minutesFormat = '';
            if (date.getMinutes() < 10) {
                minutesFormat += "0" + date.getMinutes();
            } else {
                minutesFormat += date.getMinutes();
            }
            return date.getHours() + ":" + minutesFormat;
        }
    },
    mounted() {
        this.getSources();
    }
}
</script>

<style scoped>

    .header-text {
        color: #8f8f8f;
    }

    .flash-style-warning {
        display: none;
        position: relative;
        top: 2px;
        left: 22px;
        background-color: rgba(245, 34, 70, 0.3);
        width: 333px;
        height: 35px;
        text-align: center;
        border-radius: 7px;
    }

</style>