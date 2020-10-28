<template>
    <div class="container">
        <div :style="switchStyleFlash" class="flex flash-container">
            <p>{{ message.text }}</p>
        </div>
        <div :style="{ display: this.showHide }" class="row justify-content-center">
            <div class="container col-md-12 mt-5">
                <div class="card-body"><h5><strong class="header-text">Lesson Plan</strong></h5></div>
                <div class="text-center pb-3">
                    <h5 class="pt-3 pb-0">
                        <strong>You Belong to Class:
                            <p class="d-inline text-danger">{{ this.class }}</p>
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
                        <tr v-for="planElement in planElements" :key="planElement.id">
                            <td class="text-center pt-3">{{ planElement.hours }}</td>
                            <td class="text-center pt-3">{{ planElement.monday }}</td>
                            <td class="text-center pt-3">{{ planElement.tuesday }}</td>
                            <td class="text-center pt-3">{{ planElement.wednesday }}</td>
                            <td class="text-center pt-3">{{ planElement.thursday }}</td>
                            <td class="text-center pt-3">{{ planElement.friday }}</td>
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
            planElements: {},
            class: {},
            switchStyleFlash: '',
            showHide: '',
            message: {
                text: 'There are no any lesson plan.',
            },
            flashStyle: {
                'position': 'relative',
                'top': '100px',
                'left': '38.7%',
                'background-color': 'rgba(245, 34, 70, 0.3)',
                'width': '250px',
                'height': '35px',
                'text-align': 'center',
                'border-radius': '7px',
                show: {
                    'display': 'block',
                    'position': 'relative',
                    'top': '100px',
                    'left': '38.7%',
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
        getLessonPlan() {
            axios.get('lesson-plan').then(response => {
                this.planElements = response.data.lessonPlan
                this.class = response.data.class
                this.changeStyle()
            });
        },

        changeStyle() {
            if (this.planElements.length < 1) {
                this.showHide = 'none'
                this.switchStyleFlash = this.flashStyle.show
            } else {
                this.switchStyleFlash = this.flashStyle
            }
        },
    },

    mounted() {
       this.getLessonPlan()
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
</style>

