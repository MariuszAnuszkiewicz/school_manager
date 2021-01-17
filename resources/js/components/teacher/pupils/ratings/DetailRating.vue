<template>
    <div class="container">
        <div class="row justify-content-center">
            <div v-if="messagesWarning !== undefined" :style="{ display: showMessageWarning }" class="flex flash-container flash-style-warning">
                <div v-for="messageWarning in messagesWarning" class="error-explode">
                   <p>{{ messageWarning }}</p>
                </div>
            </div>
            <div v-if="messagesWarning[0] === undefined" class="col mt-5">
                <div class="card-body"><h5><strong class="header-text">Detail Ratings</strong></h5></div>
                <table class="table table-striped">
                    <thead class="bg-dark">
                        <tr>
                            <th class="text-center text-white">User Id</th>
                            <th class="text-center text-white">User Name</th>
                            <th class="text-center text-white">Subject</th>
                            <th class="text-center text-white">Ratings</th>
                            <th class="text-center text-white">Date Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(rating, index) in ratings">
                            <td class="text-center pt-3">{{ users.id }}</td>
                            <td class="text-center pt-3">{{ users.name }}</td>
                            <td class="text-center pt-3">{{ subject.name }}</td>
                            <td class="text-center pt-3">{{ rating.rating }}</td>
                            <td class="text-center pt-3">{{ createdAt[index] | formatDate(createdAt[index]) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            users: {},
            ratings: {},
            subject: {},
            createdAt: {},
            messagesWarning: [],
            showMessageWarning: 'none',
            message: {
                warningText: 'There are no any ratings for pupil.',
            },
        }
    },
    methods: {
        getSources() {
            let id = window.location.href.split('/').pop();
            axios.get('/teacher/detail-rating/' + id).then(response => {
                this.users = response.data.users;
                this.ratings = response.data.ratings;
                this.subject = response.data.subject;
                this.createdAt = response.data.createdAt;
                this.showWarning(response.data.message);
            });
        },
        showWarning(warningText) {
            if (warningText !== undefined) {
                this.messagesWarning.push(warningText);
                this.messagesWarning.splice(1, this.messagesWarning.length);
                this.showMessageWarning = 'block';
            }
        },
    },
    filters: {
        formatDate(value) {
            var date = new Date(value);
            let timestamps = '';
            let minutesFormat = '';

            if (date.getMinutes() < 10) {
                minutesFormat += "0" + date.getMinutes();
            } else {
                minutesFormat += date.getMinutes();
            }

            if (value === null) {
                return timestamps = "";
            } else {
                return timestamps +=
                    " " + date.getFullYear() +
                    "-" + (date.getMonth() + 1) +
                    "-" + date.getDate() +
                    " " + date.getHours() +
                    ":" + minutesFormat;
            }
        }
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
    .error-explode p {
        padding-top: 5px;
    }

</style>
