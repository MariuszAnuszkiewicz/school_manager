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
            <div class="col mt-5">
                <div class="card-body"><h5><strong class="header-text">Fill Presence</strong></h5></div>
                <table class="table table-striped">
                    <thead class="bg-dark">
                        <tr>
                            <th class="text-center text-white">Select</th>
                            <th class="text-center text-white">User Id</th>
                            <th class="text-center text-white">User Name</th>
                            <th class="text-center text-white">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <div class="custom-control text-center pt-2 bg-warning">
                            <label class="check-all-label"><strong>Select All</strong></label>
                            <input @click="selectAll()" type="checkbox" class="select-all ml-2">
                        </div>
                        <div class="col-md-12 text-center pt-2 pb-2 bg-dark">
                            <button id="yes-presence" class="btn btn-outline-info" :data-presence="presenceYes"
                                    @click="setPresence(presenceYes)">
                                    <i class="fas fa-check"></i>
                            </button>
                            <button id="no-presence" class="btn btn-outline-danger" :data-presence="presenceNo"
                                    @click="setPresence(presenceNo)">
                                    <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <tr v-for="user in users.data" :key="user.id">
                            <td class="text-center pt-3">
                                <input type="checkbox" class="users-select"
                                       v-model="selected"
                                       :value="user.id">
                            </td>
                            <td class="text-center pt-3">{{ user.id }}</td>
                            <td class="text-center pt-3">{{ user.name }}</td>
                            <td class="text-center">
                                <a v-bind:href="'detail-presence/' + user.id">
                                    <button class="btn btn-info"><i class="fas fa-eye"></i></button>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <pagination :data="users" @pagination-change-page="getSources"></pagination>
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
            selected: [],
            messagesInfo: [],
            messagesWarning: [],
            isSelected: false,
            presenceYes: 'yes',
            presenceNo: 'no',
            showMessageInfo: 'none',
            showMessageWarning: 'none',
        }
    },
    methods: {
        getSources(page) {
            if (typeof page === 'undefined') {
                page = 1;
            }
            axios.get('fill-presence?page=' + page).then(response => {
                this.users = response.data.users;
                this.pupils = response.data.pupils;
            });
        },
        selectAll() {
            this.isSelected = !this.isSelected;
            if (this.isSelected) {
                for(let item in this.users.data){
                    this.selected.push(this.users.data[item].id);
                }
            } else {
                this.selected.splice(0, this.selected.length);
            }
        },
        setPresence(marker) {
            var elNo = document.querySelector('#no-presence');
            var elYes = document.querySelector('#yes-presence');
            var presence = undefined;
            switch (marker) {
                case 'yes':
                    presence = elYes.dataset.presence;
                    if (this.selected.length > 0) {
                        axios.post('save-presence', {
                            userId: this.selected,
                            presence: presence,
                        }).then(response => {
                            if (response.data.message == 'presences has been saved') {
                                this.showInfo(response.data.message);
                            } else {
                                this.showWarning(response.data.message);
                            }
                        });
                    }
                break;
                case 'no':
                    presence = elNo.dataset.presence;
                    if (this.selected.length > 0) {
                        axios.post('save-presence', {
                            userId: this.selected,
                            presence: presence,
                        }).then(response => {
                            if (response.data.message == 'presences has been saved') {
                                this.showInfo(response.data.message);
                            } else {
                                this.showWarning(response.data.message);
                            }
                        });
                    }
                break;
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
            if (this.messagesWarning !== null) {
                this.messagesWarning.push(warningText);
                this.messagesWarning.splice(1, this.messagesWarning.length);
                this.showMessageWarning = 'block';
            }
        },
    },
    mounted() {
        this.setPresence()
        this.getSources()
    }
}
</script>

<style scoped>

    .header-text {
        color: #8f8f8f;
    }
    .users-select, .select-all {
        position: relative;
        top: 3px;
        left: 0px;
        width: 18px;
        height: 18px;
        background-color: #fffed5;
    }
    .flash-style-info {
        display: none;
        position: absolute;
        top: 120px;
        left: 42.1%;
        background-color: rgba(60, 204, 102, 0.3);
        width: 333px;
        height: 35px;
        text-align: center;
        border-radius: 7px;
    }
    .flash-style-warning {
        display: none;
        position: absolute;
        top: 120px;
        left: 42.1%;
        background-color: rgba(245, 34, 70, 0.3);
        width: 333px;
        height: 35px;
        text-align: center;
        border-radius: 7px;
    }
    .error-explode p {
        padding-top: 5px;
    }

</style>
