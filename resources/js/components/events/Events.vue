<template>
    <div class="container">
        <div v-if="(this.quantityRows === 0)" :style="flashStyle" class="flex flash-container">
            <p :style="flashStyle.p">{{ message.text }}</p>
        </div>
        <div v-if="(this.quantityRows > 0) ? this.showHide = 'block' : this.showHide = 'none'"
            :style="{ display: this.showHide }"
            class="row justify-content-center">
            <div class="container col-md-12 mt-5">
                <div class="card-body"><h5><strong class="header-text">Scheduling Tests</strong></h5></div>
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center pt-2">Teacher</th>
                            <th class="text-center pt-2">Title</th>
                            <th class="text-center pt-2">Day</th>
                            <th class="text-center pt-2">Hour Start</th>
                            <th class="text-center pt-2">Hour End</th>
                            <th class="text-center pt-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(event, index) in events.data" :key="event.id">
                            <td class="text-center pt-3">{{ teachers[index] }}</td>
                            <td class="text-center pt-3">{{ event.title }}</td>
                            <td class="text-center pt-3">{{ event.day }}</td>
                            <td class="text-center pt-3">{{ event.hour_start }}</td>
                            <td class="text-center pt-3">{{ event.hour_end }}</td>
                            <td>
                                <button @click.prevent="deleteEvent(event)" class="btn btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <pagination :data="events" @pagination-change-page="getEvents"></pagination>
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
            quantityRows: 0,
            showHide: '',
            message: {
                text: 'There are no any content.',
            },
            flashStyle: {
                'position': 'relative',
                'top': '100px',
                'left': '38.7%',
                'display': 'block',
                'background-color': 'rgba(245, 34, 70, 0.3)',
                'width': '250px',
                'height': '35px',
                'text-align': 'center',
                'border-radius': '7px',
                p: {
                    'position': 'relative',
                    'top': '4px',
                }
            },
        }
    },

    methods: {
        getEvents(page) {
            if (typeof page === 'undefined') {
                page = 1;
            }
            axios.get('events?page=' + page).then(response => {
                this.events = response.data.events
                this.teachers = response.data.teachers

                console.log(this.events)
                console.log(this.teachers)
            });
        },

        deleteEvent(event) {
            let _this = this;
            axios.delete('events/' + event.id).then(response => {
                _this.getEvents();
            });
        },

        resultCount() {
            axios.get('events').then(response => {
                this.quantityRows = Object.values(response.data.events)[1].length
            });
        }
    },

    mounted() {
        this.getEvents()
        this.resultCount()
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
</style>
