<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col mt-5">
                <div class="card-body"><h5><strong class="header-text">Assign Rating</strong></h5></div>
                <table class="table table-striped">
                    <thead class="bg-dark">
                        <tr>
                            <th class="text-center text-white">User Id</th>
                            <th class="text-center text-white">User Name</th>
                            <th class="text-center text-white">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, i) in users.data" :key="user.id">
                            <td class="text-center pt-3">{{ user.id }}</td>
                            <td class="text-center pt-3">{{ user.name }}</td>
                            <td class="text-center">
                                <button class="btn btn-primary" @click="openSaveModal(user.id)">
                                    <i class="fas fa-star"></i>
                                </button>
                                <button class="btn btn-success" @click="openEditModal(user.id, pupils[i])">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger" @click="openDeleteModal(user.id, pupils[i])">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <pagination :data="users" @pagination-change-page="getSources"></pagination>
            </div>
        </div>
        <save-rating v-if="showModal.save === true"
                     :userId="user.id"
                     :subjects="subjects">
            <h3 slot="header" class="modal-title">
                Save Rating
            </h3>
            <div slot="footer">
                <button type="button" class="btn btn-outline-info" @click="closeModal">Close</button>
            </div>
        </save-rating>
        <edit-rating v-if="showModal.edit === true"
                     :userId="user.id"
                     :ratings="ratings"
                     :createAt="createAt"
                     :subjects="subjects">
            <h3 slot="header" class="modal-title">
                Edit Rating
            </h3>
            <div slot="footer">
                <button type="button" class="btn btn-outline-info" @click="closeModal">Close</button>
            </div>
        </edit-rating>
        <delete-rating v-if="showModal.delete === true"
                       :userId="user.id"
                       :ratings="ratings"
                       :createAt="createAt"
                       :subjects="subjects">
            <h3 slot="header" class="modal-title">
                Delete Rating
            </h3>
            <div slot="footer">
                <button type="button" class="btn btn-outline-info" @click="closeModal">Close</button>
            </div>
        </delete-rating>
    </div>
</template>

<script>
export default {
    data() {
        return {
            users: {},
            pupils: {},
            subjects: {},
            semesters: {},
            ratings: {},
            createAt: {},
            showModal: {
                save: false,
                edit: false,
                delete: false,
            },
            user: {
                id: '',
                name: '',
            },
        }
    },
    methods: {
        getSources(page = null) {
            if (typeof page === 'undefined') {
                page = 1;
            }
            axios.get('assign-rating?page=' + page).then(response => {
                this.users = response.data.users
                this.pupils = response.data.pupils
                this.subjects = response.data.subjects
            });
        },
        getRatingsByPupilId(pupilId){
            axios.get('pupil-rating/' + pupilId).then(response => {
                this.ratings = response.data.ratings
                this.createAt = response.data.createAt
            });
        },
        openSaveModal(userId) {
            this.user.id = userId;
            this.showModal.save = true;
        },
        openEditModal(userId, pupil) {
            this.user.id = userId;
            this.showModal.edit = true;
            this.getRatingsByPupilId(pupil.id);
        },
        openDeleteModal(userId, pupil) {
            this.user.id = userId;
            this.showModal.delete = true;
            this.getRatingsByPupilId(pupil.id);
        },
        closeModal() {
            this.showModal.save = false;
            this.showModal.edit = false;
            this.showModal.delete = false;
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

</style>
