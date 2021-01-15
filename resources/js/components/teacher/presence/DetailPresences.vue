<template>
    <div class="container">
        <div class="row justify-content-center">
            <div v-if="alerts !== undefined" :style="{ display: showHide }" class="flex flash-container flash-style-warning">
                <div v-for="alert in alerts" class="error-explode">
                    <p>{{ alert }}</p>
                </div>
            </div>
            <div v-if="alerts[0] === undefined" class="col mt-5">
                <div class="card-body"><h5><strong class="header-text">Detail Presences</strong></h5></div>
                <table class="table table-striped">
                    <thead class="bg-dark">
                        <tr>
                            <th class="text-center text-white">User Id</th>
                            <th class="text-center text-white">User Name</th>
                            <th class="text-center text-warning">Presences</th>
                            <th class="text-center text-white">Presence Date</th>
                            <th class="text-center text-white">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="presence in presences.data">
                            <td class="text-center pt-3">{{ user.id }}</td>
                            <td class="text-center pt-3">{{ user.name }}</td>
                            <td v-if="presence.presence == 'no'" class="text-center pt-3 text-danger">
                                <b><img v-bind:src="'/images/user/icons/' + crossIcon"></b>
                            </td>
                            <td v-if="presence.presence == 'yes'" class="text-center pt-3 text-success">
                                <b><img v-bind:src="'/images/user/icons/' + confirmIcon"></b>
                            </td>
                            <td class="text-center pt-3">{{ presence.date }}</td>
                            <td class="text-center">
                                <button id="edit-presence" class="btn btn-success" @click="openModalEdit(presence)">
                                   <i class="fas fa-edit"></i>
                                </button>
                                <a v-bind:href="'delete/' + presence.id">
                                    <button @click.prevent="deletePresence(presence)" class="btn btn-danger">
                                       <i class="fas fa-trash"></i>
                                    </button>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <pagination :data="presences" @pagination-change-page="getSources"></pagination>
            </div>
        </div>
        <edit-presence v-if="showModalEdit === true" :presenceData="presenceData">
            <h3 slot="header" class="modal-title">
               Edit Presence
            </h3>
            <div slot="body">

            </div>
            <div slot="footer">
               <button type="button" class="btn btn-outline-info" @click="closeModalEdit">Close</button>
            </div>
        </edit-presence>
    </div>
</template>

<script>
export default {
    data() {
        return {
            user: {},
            presences: {},
            alerts: [],
            showHide: 'none',
            presenceData: undefined,
            showModalEdit: false,
            crossIcon: 'cross.png',
            confirmIcon: 'confirm.png',
        }
    },
    methods: {
        getSources(page) {
            if (typeof page === 'undefined') {
                page = 1;
            }
            let id = window.location.href.split('/').pop();
            axios.get('/teacher/detail-presence/' + id + '?page=' + page).then(response => {
                this.user = response.data.user;
                if (response.data.presences) {
                    this.presences = response.data.presences;
                } else {
                    this.showWarning(response.data.message);
                }
            });
        },
        deletePresence(presence) {
            let _this = this;
            axios.delete('/teacher/detail-presence/delete/' + presence.id).then(response => {
                _this.getSources()
            });
        },
        showWarning(warningText) {
            if (this.alerts !== null) {
                this.alerts.push(warningText);
                this.showHide = 'block';
            }
        },
        openModalEdit(presence) {
            this.presenceData = presence;
            this.showModalEdit = true;
        },
        closeModalEdit() {
            setTimeout(() => {
                this.showModalEdit = false;
            }, 150);
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
    .error-explode p {
        padding-top: 5px;
    }
    .flash-style-warning {
        display: none;
        position: absolute;
        top: 250px;
        left: 42.2%;
        background-color: rgba(245, 34, 70, 0.3);
        width: 333px;
        height: 35px;
        text-align: center;
        border-radius: 7px;
    }

</style>
