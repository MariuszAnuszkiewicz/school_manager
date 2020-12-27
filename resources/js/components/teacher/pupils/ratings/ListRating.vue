<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col mt-5">
                <div class="card-body"><h5><strong class="header-text">List Ratings</strong></h5></div>
                <table class="table table-striped">
                    <thead class="bg-dark">
                        <tr>
                            <th class="text-center text-white">User Id</th>
                            <th class="text-center text-white">User Name</th>
                            <th class="text-center text-white">Subject</th>
                            <th class="text-center text-white">Ratings</th>
                            <th class="text-center text-white">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users.data" :key="user.id">
                            <td class="text-center pt-3">{{ user.id }}</td>
                            <td class="text-center pt-3">{{ user.name }}</td>
                            <td class="text-center pt-3">{{ subject.name }}</td>
                            <td v-if="user.id" class="text-center pt-3 text-danger"><b>{{ ratings[user.id] }}</b></td>
                            <td class="text-center">
                                <a v-bind:href="'detail-rating/' + user.id">
                                    <button class="btn btn-info"><i class="fas fa-eye"></i></button>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <pagination :data="users" @pagination-change-page="getSources"></pagination>
    </div>
</template>

<script>
export default {
    data() {
        return {
            users: {},
            ratings: {},
            subject: {},
        }
    },
    methods: {
        getSources(page) {
            if (typeof page === 'undefined') {
                page = 1;
            }
            axios.get('list-ratings?page=' + page).then(response => {
                this.users = response.data.users;
                this.ratings = response.data.ratings;
                this.subject = response.data.subject;
            });
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

</style>
