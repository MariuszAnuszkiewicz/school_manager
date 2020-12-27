<template>
    <div class="container">
        <div class="row justify-content-center">
            <div :style="switchFlashStyle" class="flex flash-container">
                <div v-if="alerts !== undefined" v-for="alert in alerts" class="error-explode">
                    <p>{{ alert }}</p>
                </div>
            </div>
            <div v-if="alerts[0] === undefined" class="col mt-5">
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
                            <td class="text-center pt-3">{{ users[0].id }}</td>
                            <td class="text-center pt-3">{{ users[0].name }}</td>
                            <td class="text-center pt-3">{{ subject[0].name }}</td>
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
            alerts: [],
            switchFlashStyle: '',
            message: {
                warningText: 'There are no any ratings for pupil.',
            },
            flashStyleWarning: {
                'display': 'none',
                show: {
                    'display': 'block',
                    'position': 'absolute',
                    'top': '110px',
                    'left': '44.5%',
                    'background-color': 'rgba(245, 34, 70, 0.3)',
                    'width': '260px',
                    'height': '35px',
                    'text-align': 'center',
                    'border-radius': '7px',
                }
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
                this.showWarning();
            });
        },
        showWarning() {
            if (this.ratings === undefined) {
                this.alerts.push(this.message.warningText);
                this.switchFlashStyle = this.flashStyleWarning.show;
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
    .error-explode p {
        padding-top: 4px;
    }

</style>
