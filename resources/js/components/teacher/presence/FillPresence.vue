<template>
    <div class="container">
        <div class="row justify-content-center">
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
                        <div class="col-md-12 text-center pt-2 pb-2 bg-light">
                            <button class="btn btn-outline-info" @click="setPresence()"><i class="fas fa-check"></i></button>
                        </div>
                        <tr v-for="(user, index) in users.data" :key="user.id">
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
            isSelected: false,
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
                console.log(this.users);
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
        setPresence() {
            if (this.selected.length > 0) {
                axios.post('save-presence', {userId: this.selected}).then(response => {

                });
            }
            console.log(this.selected);
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



</style>
