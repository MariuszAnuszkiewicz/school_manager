<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col mt-5">
                <div class="card-body"><h5><strong class="header-text">List Pupils</strong></h5></div>
                <table class="table table-striped">
                    <thead class="bg-dark">
                        <tr>
                            <td class="text-center text-white pt-2">Select Email</td>
                            <td class="text-center text-white pt-2">Id</td>
                            <td class="text-center text-white pt-2">Name</td>
                            <td class="text-center text-white pt-2">Email</td>
                        </tr>
                    </thead>
                    <tbody>
                        <div class="custom-control text-center pt-2 bg-warning">
                            <label class="check-all-label"><strong>Select All</strong></label>
                            <input @click="selectAll()" type="checkbox" id="select-all" class="select-all d-inline-block ml-2">
                        </div>
                        <div class="col-md-12 text-center d-inline-block pt-2 pb-2 bg-light">
                            <button class="btn btn-outline-success" @click="openModal()"><i class="fas fa-mail-bulk"></i></button>
                        </div>
                        <tr v-for="pupil in pupils.data" :key="pupil.id">
                            <td class="text-center pt-3">
                                <input type="checkbox" class="pupils-select"
                                       v-model="selectedEmails"
                                       :value="pupil.email">
                            </td>
                            <td class="text-center pt-3">{{ pupil.id }}</td>
                            <td class="text-center pt-3">{{ pupil.name }}</td>
                            <td class="text-center pt-3">{{ pupil.email }}</td>
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
        }
    },
    methods: {
        getPupils(page) {
            if (typeof page === 'undefined') {
                page = 1;
            }
            axios.get('list-emails?page=' + page).then(response => {
                this.pupils = response.data.pupils
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
            this.showModal = false;
            setTimeout(() => {
                this.showHide = 'block';
            }, 500);
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

</style>
