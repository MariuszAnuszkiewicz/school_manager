<template>
    <div class="container">
        <div class="row justify-content-center">
            <div v-if="messagesWarning !== undefined" :style="{ display: showMessageWarning }" class="flex flash-container flash-style-warning">
                <div v-for="messageWarning in messagesWarning" class="error-explode">
                    <p>{{ messageWarning }}</p>
                </div>
            </div>
            <div v-if="messagesWarning[0] === undefined" class="col mt-5">
                <div class="card-body"><h5><strong class="header-text">List Emails To Send</strong></h5></div>
                <table class="table table-striped">
                    <thead class="bg-dark">
                        <tr>
                            <th class="text-center text-white">Select Email</th>
                            <th class="text-center text-white">User Id</th>
                            <th class="text-center text-white">User Name</th>
                            <th class="text-center text-white">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <div class="custom-control text-center pt-2 bg-warning">
                            <label class="check-all-label"><strong>Select All</strong></label>
                            <input @click="selectAll()" type="checkbox" id="select-all" class="select-all ml-2">
                        </div>
                        <div class="col-md-12 text-center pt-2 pb-2 bg-light">
                            <button class="btn btn-outline-success" @click="openModal()"><i class="fas fa-mail-bulk"></i></button>
                        </div>
                        <tr v-for="pupil in pupils.data" :key="pupil.id">
                            <td class="text-center pt-3">
                                <input type="checkbox" class="pupils-select"
                                       v-model="selectedEmails"
                                       :value="pupil.email">
                            </td>
                            <td class="text-center">{{ pupil.id }}</td>
                            <td class="text-center">{{ pupil.name }}</td>
                            <td class="text-center">{{ pupil.email }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <pagination :data="pupils" @pagination-change-page="getPupils"></pagination>
        <send-emails :selectedEmails="selectedEmails" v-if="showModal === true">
            <h3 slot="header" class="modal-title">
                Send Emails
            </h3>
            <div slot="body">

            </div>
            <div slot="footer">
                <button type="button" class="btn btn-outline-info" @click="closeModal">Close</button>
            </div>
        </send-emails>
    </div>
</template>

<script>
export default {
    data() {
        return {
            pupils: {},
            selectedEmails: [],
            isSelected: false,
            showModal: false,
            messagesWarning: [],
            showMessageWarning: 'none',
        }
    },
    methods: {
        getPupils(page) {
            if (typeof page === 'undefined') {
                page = 1;
            }
            axios.get('list-emails?page=' + page).then(response => {
                this.pupils = response.data.pupils
                if (this.pupils === undefined) {
                    this.showWarning(response.data.message);
                }
            });
        },
        selectAll() {
            this.isSelected = !this.isSelected;
            if (this.isSelected) {
                for(let item in this.pupils.data){
                    this.selectedEmails.push(this.pupils.data[item].email);
                }
            } else {
                this.selectedEmails.splice(0, this.selectedEmails.length);
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
        showWarning(warningText) {
            if (this.messagesWarning !== null) {
                this.messagesWarning.push(warningText);
                this.messagesWarning.splice(1, this.messagesWarning.length);
                this.showMessageWarning = 'block';
            }
        },
    },
    mounted() {
        this.getPupils()
    }
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
    .pupils-select {
        position: relative;
        top: 3px;
        left: 0px;
        width: 18px;
        height: 18px;
        background-color: #fffed5;
    }
    .flash-style-warning {
        display: none;
        position: absolute;
        top: 250px;
        left: 42.1%;
        background-color: rgba(245, 34, 70, 0.3);
        width: 333px;
        height: 35px;
        text-align: center;
        border-radius: 7px;
    }

</style>
