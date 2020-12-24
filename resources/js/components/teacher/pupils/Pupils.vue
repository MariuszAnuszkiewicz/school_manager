<template>
    <div class="container">
        <div class="row justify-content-center">
            <div :style="switchFlashStyle" class="flex flash-container">
                <div v-if="alerts !== undefined" v-for="alert in alerts" class="error-explode">
                    <p>{{ alert }}</p>
                </div>
            </div>
            <div v-if="alerts[0] === undefined" class="col mt-5">
                <form @submit.prevent="submitForm()" method="POST" id="classesForm" class="form-horizontal">
                    <div class="col-md-12 text-center">
                        <select id="selectClass" class="mt-2">
                            <option v-for="class_in_school in classes_in_school"
                                    :value="class_in_school.id">{{ class_in_school.name }}
                            </option>
                        </select>
                        <button class="btn btn-primary ml-2 mt-2 mb-2" type="submit">Submit</button>
                    </div>
                    <div class="col-md-12 mt-5">
                        <div class="card-body"><h5><strong class="header-text">List of Pupils</strong></h5></div>
                        <table class="table table-striped">
                            <thead class="bg-dark">
                                <tr>
                                    <th class="text-center text-white">Select Id</th>
                                    <th class="text-center text-white">Pupil Id</th>
                                    <th class="text-center text-white">User Id</th>
                                    <th class="text-center text-white">Classes</th>
                                    <th class="text-center text-white">Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <div class="custom-control custom-switch text-center pt-2 bg-warning">
                                   <label class="check-all-label"><strong>Select All</strong></label>
                                   <input @click="selectAll()" type="checkbox" id="select-all" class="select-all d-inline-block ml-2">
                                </div>
                                <tr v-for="(user, index) in users" :key="user.id">
                                    <td class="text-center">
                                        <input type="checkbox" class="pupil-select"
                                               v-model="selected"
                                               :value="pupils[index].id"
                                               @change="unSelectAll()">
                                    </td>
                                    <td class="text-center">{{ pupils[index].id }}</td>
                                    <td class="text-center">{{ user.id }}</td>
                                    <td class="text-center">{{ assign_classes[index].name }}</td>
                                    <td class="text-center">{{ user.name.slice(0, 65) + ' ... '  }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
   </div>
</template>

<script>
export default {
    data() {
        return {
            users: {},
            pupils: {},
            classes_in_school: {},
            assign_classes: {},
            selected: [],
            alerts: [],
            isSelected: false,
            switchFlashStyle: '',
            isUpdate: false,
            message: {
               text: 'There are no any pupils.',
            },
            flashStyleWarning: {
                'display': 'none',
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
            flashStyleInfo: {
               'display': 'none',
                show: {
                    'display': 'block',
                    'position': 'relative',
                    'top': '150px',
                    'left': '38.7%',
                    'background-color': 'rgba(60, 204, 102, 0.3)',
                    'width': '250px',
                    'height': '35px',
                    'text-align': 'center',
                    'border-radius': '7px',
                }
            },
        }
    },
    methods: {
        getSources() {
            axios.get('pupils').then(response => {
                this.users = response.data.users;
                this.pupils = response.data.pupils;
                this.classes_in_school = response.data.classes_in_school;
                this.assign_classes = response.data.assign_classes;
                this.showWarning();
            });
        },
        submitForm() {
            if (this.selected.length > 0) {
                let formData = new FormData(document.getElementById('classesForm'));
                formData.append('class_assign', document.querySelector('#selectClass').value);
                formData.append('pupils', this.selected);
                axios.post('pupils', formData).then(response => {
                    this.message.text = response.data.message;
                }).catch(function (error) {
                    console.log(error.response.data);
                });
                this.isUpdate = true;
                this.showInfo();
            }
        },
        selectAll() {
            this.isSelected = !this.isSelected;
            if (this.isSelected) {
                for (let i = 0; i < this.pupils.length; i++) {
                   this.selected.push(this.pupils[i].id);
                }
            } else {
                this.selected.splice(0, this.selected.length);
            }
        },
        unSelectAll() {
            if (this.selected.length > 0) {
               this.isSelected = true;
            } else {
               this.isSelected = false;
            }
        },
        showWarning() {
            if (this.pupils === undefined) {
                this.alerts.push(this.message.text);
                this.switchFlashStyle = this.flashStyleWarning.show
            } else {
                this.switchFlashStyle = this.flashStyleWarning
            }
        },
        showInfo() {
           if (this.isUpdate === true) {
               this.switchFlashStyle = this.flashStyleInfo.show
           } else {
               this.switchFlashStyle = this.flashStyleInfo
           }
        }
    },
    mounted() {
        this.getSources()
        this.submitForm()
    },
}
</script>

<style scoped>

    .header-text {
        color: #8f8f8f;
    }
    .select-all {
        position: relative;
        top: 3px;
        left: 0px;
        width: 18px;
        height: 18px;
        background-color: #fffed5;
    }
    .pupil-select {
        position: relative;
        top: 3px;
        left: 0px;
        width: 18px;
        height: 18px;
        background-color: #fffed5;
    }
    .flash-container {
        display: none;
    }
    .flash-container p {
        position: relative;
        top: 4px;
    }

</style>
