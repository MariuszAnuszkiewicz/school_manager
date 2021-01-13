<template>
    <div class="container">
        <div class="row justify-content-center bg-light pb-5">
            <div class="col mt-5">
                <div id="list-events-container">
                    <div class="col-md-12 bg-light">
                        <div class="btn-group btn-group-toggle">
                            <label class="btn btn-dark" :class="{ active: (selectComponent == 'events') }">
                               <input type="radio" v-model="selectComponent" value="events">
                               Events List
                            </label>
                        </div>
                    </div>
                    <component :is="selectComponentTrigger"></component>
                </div>
                <div class="card-body"><h5><strong class="header-text">Create Event</strong></h5></div>
                <div class="text-center">
                   <button class="btn btn-dark" @click="toggleWeekends">toggle weekends</button>
                </div>
                <div id="calendar">
                   <full-calendar :options="calendarOptions"></full-calendar>
                </div>
            </div>
        </div>
        <save-event v-if="showModalSave === true"
                    :date="date"
                    :start="start"
                    :end="end"
                    :teacherId="teacherId">
                    <h3 slot="header" class="modal-title">Save Event</h3>
                    <div slot="body"></div>
                    <div slot="footer">
                       <button type="button" class="btn btn-outline-info" @click="closeModal">Close</button>
                    </div>
        </save-event >
    </div>
</template>

<script>
import fullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import interactionPlugin from '@fullcalendar/interaction';
import listEvents from './ListEvent';
export default {
    components: {
        fullCalendar,
        listEvents,
    },
    data() {
        return {
            calendarOptions: {
                plugins: [dayGridPlugin, interactionPlugin],
                locale: 'en',
                initialView: 'dayGridMonth',
                weekends: false,
                selectable: true,
                select: this.createEvent,
            },
            teacherId: {},
            start: {},
            end: {},
            date: '',
            showModalSave: false,
            selectComponent: false,
        }
    },
    computed: {
        selectComponentTrigger() {
            return this.selectComponent == 'events' ? listEvents : false;
        }
    },
    methods: {
        getSources() {
            axios.get('events-by-calendar').then(response => {
                this.teacherId = response.data.teacher.id;
                this.start = response.data.start;
                this.end = response.data.end;
            });
        },
        createEvent(select) {
            if (select) {
                this.openModal();
                this.date = select.startStr;
            }
        },
        openModal() {
            this.showModalSave = true;
        },
        closeModal() {
            setTimeout(() => {
                this.showModalSave = false;
            }, 150);
        },
        toggleWeekends: function() {
            this.calendarOptions.weekends = !this.calendarOptions.weekends;
        },
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
    #list-events-container {
        position: relative;
        top: 15px;
        left: 0px;
        margin: 5px 5px 105px 5px;
    }
    .btn-group {
        position: relative;
        top: 0px;
        left: 45.5%;
        display: block;
    }

</style>
