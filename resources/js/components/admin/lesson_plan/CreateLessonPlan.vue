<template>
    <div class="container">
        <div class="row justify-content-center">
            <div v-if="messagesInfo !== undefined" :style="{ display: showMessageInfo }" class="flex flash-container flash-style-info">
                <div v-for="messageInfo in messagesInfo" class="error-explode">
                    <p>{{ messageInfo }}</p>
                </div>
            </div>
            <div v-if="messagesWarning !== undefined" :style="{ display: showMessageWarning }" class="flex flash-container flash-style-warning">
                <div v-for="messageWarning in messagesWarning" class="error-explode">
                    <p>{{ messageWarning }}</p>
                </div>
            </div>
            <div v-if="messagesWarning[0] === undefined" class="col mt-5">
                <div class="card-body"><h5><strong class="header-text">Create Lesson Plan</strong></h5></div>
                <div class="text-center pb-3">
                    <h5 class="pt-3 pb-0">
                        <strong>Lesson Plan Class:
                            <p class="d-inline text-danger">{{ nameClass.name }}</p>
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
                            <th class="text-center pt-2">Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(plan, index) in lessonPlan" :key="plan.id">
                            <td class="text-center pt-3">{{ plan.hours }}</td>
                            <td class="text-center pt-3" @click.once.prevent="onSubjectsOptions(index)">
                                <div v-if="dataIndex === index">
                                    <select id="monday" @change="onChange(plan.hours, $event)">
                                       <option>{{ '- select subject -' }}</option>
                                       <option v-for="subject in subjects" :value="'monday|' + subject.name">{{ subject.name }}</option>
                                       <option :value="'monday|' + '----'">{{ '----' }}</option>
                                    </select>
                                </div>
                                <div v-else>
                                    {{ plan.monday }}
                                </div>
                            </td>
                            <td class="text-center pt-3" @click.once.prevent="onSubjectsOptions(index)">
                                <div v-if="dataIndex === index">
                                    <select id="tuesday" @change="onChange(plan.hours, $event)">
                                       <option>{{ '- select subject -' }}</option>
                                       <option v-for="subject in subjects" :value="'tuesday|' + subject.name">{{ subject.name }}</option>
                                       <option :value="'tuesday|' + '----'">{{ '----' }}</option>
                                    </select>
                                </div>
                                <div v-else>
                                    {{ plan.tuesday }}
                                </div>
                            </td>
                            <td class="text-center pt-3" @click.once.prevent="onSubjectsOptions(index)">
                                <div v-if="dataIndex === index">
                                    <select id="wednesday" @change="onChange(plan.hours, $event)">
                                       <option>{{ '- select subject -' }}</option>
                                       <option v-for="subject in subjects" :value="'wednesday|' + subject.name">{{ subject.name }}</option>
                                       <option :value="'wednesday|' + '----'">{{ '----' }}</option>
                                    </select>
                                </div>
                                <div v-else>
                                    {{ plan.wednesday }}
                                </div>
                            </td>
                            <td class="text-center pt-3" @click.once.prevent="onSubjectsOptions(index)">
                                <div v-if="dataIndex === index">
                                    <select id="thursday" @change="onChange(plan.hours, $event)">
                                       <option>{{ '- select subject -' }}</option>
                                       <option v-for="subject in subjects" :value="'thursday|' + subject.name">{{ subject.name }}</option>
                                       <option :value="'thursday|' + '----'">{{ '----' }}</option>
                                    </select>
                                </div>
                                <div v-else>
                                    {{ plan.thursday }}
                                </div>
                            </td>
                            <td class="text-center pt-3" @click.once.prevent="onSubjectsOptions(index)">
                                <div v-if="dataIndex === index">
                                    <select id="friday" @change="onChange(plan.hours, $event)">
                                       <option>{{ '- select subject -' }}</option>
                                       <option v-for="subject in subjects" :value="'friday|' + subject.name">{{ subject.name }}</option>
                                       <option :value="'friday|' + '----'">{{ '----' }}</option>
                                    </select>
                                </div>
                                <div v-else>
                                    {{ plan.friday }}
                                </div>
                            </td>
                            <td class="text-center">
                                <button type="button" id="update-plan" class="btn btn-success" @click="updatePlan()">
                                    Update
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
       <div v-if="messagesWarning[0] !== undefined" class="text-center mt-3">
          <new-lesson-plan></new-lesson-plan>
       </div>
    </div>
</template>

<script>
import NewLessonPlan from "./NewLessonPlan";
export default {
    components: {
        NewLessonPlan,
    },
    data() {
        return {
            lessonPlan: {},
            subjects: {},
            nameClass: {},
            selected: [],
            dataIndex: '',
            messagesInfo: [],
            messagesWarning: [],
            showMessageInfo: 'none',
            showMessageWarning: 'none',
        }
    },
    methods: {
        getSources() {
            let id = window.location.href.split('/').pop();
            axios.get('/admin/create-lesson-plan/' + id).then(response => {
                this.lessonPlan = response.data.lessonPlan;
                this.subjects = response.data.subjects;
                this.nameClass = response.data.nameClass;
                if (response.data.lessonPlan === undefined) {
                    this.showWarning(response.data.message);
                }
            });
        },
        onSubjectsOptions(index) {
            this.dataIndex = index;
        },
        onChange(hours, event) {
            this.selected.push(hours + "|" + event.target.value);
            this.selected.length > 5 ? this.selected.splice(1, this.selected.length) : this.selected;
        },
        updatePlan() {
            if (this.selected.length > 0) {
                axios.post('/admin/update-lesson-plan', {
                    data: this.selected,
                }).then(response => {
                    this.showInfo(response.data.message);
                }).catch(function (error) {
                    console.log(error.response.data)
                });
            }
        },
        showInfo(infoText) {
            if (this.messagesInfo !== null) {
                this.messagesInfo.push(infoText);
                this.messagesInfo.splice(1, this.messagesInfo.length);
                this.showMessageInfo = 'block';
            }
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
    .flash-container p {
        position: relative;
        top: 5px;
    }
    .flash-style-info {
        display: block;
        position: relative;
        top: 85px;
        left: 0px;
        background-color: rgba(60, 204, 102, 0.3);
        width: 333px;
        height: 35px;
        text-align: center;
        border-radius: 7px;
        margin: 0px 0px 15px 0px;
    }
    .flash-style-warning {
        display: block;
        position: relative;
        top: 85px;
        left: 0px;
        background-color: rgba(245, 34, 70, 0.3);
        width: 333px;
        height: 35px;
        text-align: center;
        border-radius: 7px;
    }

</style>