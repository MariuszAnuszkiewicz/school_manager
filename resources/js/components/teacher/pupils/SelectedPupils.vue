<template>
    <div class="container">
        <div :style="switchFlashStyle" class="flex flash-container">
          <p>{{ message.text }}</p>
        </div>
        <div v-if="errors" :style="{ display: showHide }" class="row justify-content-center">
            <div class="col mt-5">
                <div class="card-body"><h5><strong class="header-text">Selected Pupils</strong></h5></div>
                <table class="table table-bordered">
                    <thead class="bg-dark">
                        <tr>
                            <td class="text-center text-white">Select Id</td>
                            <td class="text-center text-white">Pupil Id</td>
                            <td class="text-center text-white">User Id</td>
                            <td class="text-center text-white">Classes</td>
                            <td class="text-center text-white">Name</td>
                            <td class="text-center text-white">Actions</td>
                        </tr>
                    </thead>
                    <tbody>
                        <div class="custom-control text-center pt-2 bg-warning">
                            <label class="check-all-label"><strong>Select All</strong></label>
                            <input @click="selectAll()" type="checkbox" id="select-all" class="select-all d-inline-block ml-2">
                        </div>
                        <div class="col-md-12  text-center d-inline-block pt-2 pb-2 bg-light">
                            <button class="btn btn-outline-danger" @click="deleteSelected()"><i class="fas fa-trash"></i></button>
                            <button id="send-multiple-message" class="btn btn-success" @click="openModal()">
                              <i class="fas fa-comments"></i>
                            </button>
                        </div>
                        <tr v-for="(user, index) in users" :key="pupils[index].id">
                            <td class="text-center pt-3">
                                <input type="checkbox" class="pupil-select"
                                       v-model="selected"
                                       :value="pupils[index].id"
                                       @change="unSelectAll()">
                            </td>
                            <td class="text-center pt-3">{{ pupils[index].id }}</td>
                            <td class="text-center pt-3">{{ user.id }}</td>
                            <td class="text-center pt-3">{{ assign_classes[index].name }}</td>
                            <td class="text-center pt-3">{{ user.name }}</td>
                            <td class="text-center">
                               <button id="send-message" class="btn btn-success" @click="openModal(pupils[index].id)">
                                  <i class="fas fa-comments"></i>
                               </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <send-message :selected="selected" :pupil_id="pupil_id" v-if="showModal === true">
            <h3 slot="header" class="modal-title">
                Send Message
            </h3>
            <div slot="footer">
               <button type="button" class="btn btn-outline-info" @click="closeModal">Close</button>
            </div>
        </send-message>
    </div>
</template>

<script>
export default {
    data() {
        return {
            users: {},
            pupils: {},
            assign_classes: {},
            selected: [],
            isSelected: false,
            showModal: false,
            switchFlashStyle: '',
            showHide: '',
            pupil_id: [],
            errors: [],
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
                  'top': '100px',
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
        getPupils() {
            axios.get('selected-pupils').then(response => {
                this.users = response.data.users
                this.pupils = response.data.pupils
                this.assign_classes = response.data.assign_classes
                this.errors.push(response.data.message);
                this.showWarning(this.errors)
            });
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
            console.log(this.selected);
        },
        unSelectAll() {
            if (this.selected.length > 0) {
                this.isSelected = true;
            } else {
                this.isSelected = false;
            }
        },
        deleteSelected() {
            axios.post('delete-pupils', {selected: this.selected}).then(response => {
               this.getPupils()
            });
        },
        showWarning(errors) {
            for (let i = 0; i < errors.length; i++) {
                if (errors[i] !== undefined) {
                   this.showHide = 'none';
                   this.switchFlashStyle = this.flashStyleWarning.show;
                } else {
                   this.showHide = 'block';
                   this.switchFlashStyle = this.flashStyleWarning
                }
            }
        },
        openModal(pupil = null) {
            this.showModal = true;
            this.pupil_id.push(pupil);
        },
        closeModal() {
            this.showModal = false;
            setTimeout(() => {
              this.showHide = 'block';
            }, 500);
        },
    },
    mounted() {
        this.getPupils()
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
    #send-message {
       padding-top: 2px;
       margin-top: 2px;
       padding-bottom: 2px;
       margin-bottom: 2px;
    }


</style>