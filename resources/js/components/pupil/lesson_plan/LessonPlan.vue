<template>
    <div class="container">
        <div class="row justify-content-center">
            <div v-if="messagesWarning.length > 0" :style="{ display: showMessageWarning }" class="flex flash-container flash-style-warning">
                <div v-for="messageWarning in messagesWarning" class="error-explode">
                    <p>{{ messageWarning }}</p>
                </div>
            </div>
            <div v-if="messagesWarning.length === 0" :style="{ display: 'block' }" class="col-md-12 mt-5">
                <div class="card-body"><h5><strong class="header-text">Lesson Plan</strong></h5></div>
                <div class="text-center pb-3">
                    <h5 class="pt-3 pb-0">
                        <strong>Your Class:
                            <p class="d-inline text-danger">{{ assignClass }}</p>
                        </strong>
                    </h5>
                </div>
                <table class="table table-striped">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center pt-2">Hours</th>
                            <th class="text-center pt-2">Monday</th>
                            <th class="text-center pt-2">Tuesday</th>
                            <th class="text-center pt-2">Wednesday</th>
                            <th class="text-center pt-2">Thursday</th>
                            <th class="text-center pt-2">Friday</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="plan in lessonPlan" :key="plan.id">
                            <td class="text-center pt-3">{{ plan.hours }}</td>
                            <td class="text-center pt-3">{{ plan.monday }}</td>
                            <td class="text-center pt-3">{{ plan.tuesday }}</td>
                            <td class="text-center pt-3">{{ plan.wednesday }}</td>
                            <td class="text-center pt-3">{{ plan.thursday }}</td>
                            <td class="text-center pt-3">{{ plan.friday }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            assignClass: {},
            lessonPlan: {},
            messagesWarning: [],
            showMessageWarning: 'none',
        }
    },
    methods: {
        getSources() {
            axios.get('lesson-plan').then(response => {
                this.assignClass = response.data.assignClass;
                if (response.data.lessonPlan !== undefined) {
                    this.lessonPlan = response.data.lessonPlan;
                } else {
                    this.showWarning(response.data.message);
                }
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
        top: 5px;
    }
    .flash-style-warning {
        display: none;
        position: absolute;
        top: 250px;
        left: 44.1%;
        background-color: rgba(245, 34, 70, 0.3);
        width: 250px;
        height: 35px;
        text-align: center;
        border-radius: 7px;
    }
</style>

