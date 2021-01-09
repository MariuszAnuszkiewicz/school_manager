<template>
    <div class="container">
        <div class="row justify-content-center bg-light pb-5">
            <div class="col mt-5">
                <div class="card-body"><h5><strong class="header-text">Create Event</strong></h5></div>
                <div class="text-center">
                     <button class="btn btn-dark" @click="toggleWeekends">toggle weekends</button>
                </div>
                <div id="calendar">
                    <full-calendar :options="calendarOptions"></full-calendar>
                </div>
            </div>
        </div>
        <save-event :date="date"
                    :start="start"
                    :end="end"
                    :teacherId="teacherId"
                    v-if="showModal === true">
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
export default {
    components: {
        fullCalendar,
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
            showModal: false,
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
            this.showModal = true;
        },
        closeModal() {
            setTimeout(() => {
                this.showModal = false;
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

</style>
