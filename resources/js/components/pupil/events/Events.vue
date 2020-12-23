<template>
    <div class="container">
        <div class="row justify-content-center">
            <div :style="switchFlashStyle" class="flex flash-container">
                <div v-if="errors !== undefined" v-for="error in errors" class="error-explode">
                    <p>{{ error }}</p>
                </div>
            </div>
            <div v-if="errors[0] === undefined" :style="{ display: showHide }" class="col mt-5">
                <div class="card-body"><h5><strong class="header-text">Scheduling Tests</strong></h5></div>
                <table class="table table-striped">
                    <thead class="bg-dark">
                        <tr>
                            <th class="text-center text-white pt-2">Teacher</th>
                            <th class="text-center text-white pt-2">Title</th>
                            <th class="text-center text-white pt-2">Day</th>
                            <th class="text-center text-white pt-2">Hour Start</th>
                            <th class="text-center text-white pt-2">Hour End</th>
                            <th class="text-center text-white pt-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody >
                        <tr v-for="(event, index) in events.data" :key="event.id">
                            <td class="text-center pt-3">{{ teachers[index] }}</td>
                            <td class="text-center pt-3">{{ event.title }}</td>
                            <td class="text-center pt-3">{{ event.day }}</td>
                            <td class="text-center pt-3">{{ event.hour_start }}</td>
                            <td class="text-center pt-3">{{ event.hour_end }}</td>
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
            errors: [],
            switchFlashStyle: '',
            showHide: '',
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
            axios.get('events?page=' + page).then(response => {
                this.events = response.data.events;
                this.teachers = response.data.teachers;
                this.errors.push(response.data.message);
                for (let i = 0; i < this.errors.length; i++) {
                    if (this.errors[i] !== undefined) {
                        this.showHide = 'none';
                        this.switchFlashStyle = this.flashStyleWarning.show;
                    } else {
                        this.showHide = 'block';
                        this.switchFlashStyle = this.flashStyleWarning;
                    }
                }
            });
        },
        deleteEvent(event) {
            let _this = this;
            axios.delete('events/' + event.id).then(response => {
               _this.getSources()
            });
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
    .error-explode p {
        padding-top: 3px;
    }

</style>
