<template>
    <div class="container">
        <div class="row justify-content-center">
            <div :style="switchFlashStyle" class="flex flash-container">
                <div v-if="errors.length > 0" v-for="error in errors" class="error-explode">
                    <p>{{ error }}</p>
                </div>
            </div>
            <div v-if="errors.length > 0" :style="{ display: showHide }" class="col mt-5">
                <div class="card-body"><h5><strong class="header-text">Detail Ratings</strong></h5></div>
                <table class="table table-striped">
                    <thead class="bg-dark">
                        <tr>
                            <td class="text-center text-white">User Id</td>
                            <td class="text-center text-white">User Name</td>
                            <td class="text-center text-white">Subject</td>
                            <td class="text-center text-white">Ratings</td>
                            <td class="text-center text-white">Created At</td>
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
            errors: [],
            switchFlashStyle: '',
            showHide: '',
            flashStyleWarning: {
                'display': 'none',
                show: {
                    'display': 'block',
                    'position': 'relative',
                    'top': '100px',
                    'left': '0%',
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
                this.errors.push(response.data.message);
                for (let i = 0; i < this.errors.length; i++) {
                    if (this.errors[i] !== undefined) {
                        this.showHide = 'none',
                        this.switchFlashStyle = this.flashStyleWarning.show;
                    } else {
                        this.showHide = 'block',
                        this.switchFlashStyle = this.flashStyleInfo;
                    }
                }
            });
        },
    },
    filters: {
        formatDate(value) {
            var date = new Date(value);
            let minutesFormat = '';
            if (date.getMinutes() < 10) {
                minutesFormat += "0" + date.getMinutes();
            } else {
                minutesFormat += date.getMinutes();
            }
            let timestamps = date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getHours();
            return timestamps += " " + date.getHours() + ":" + minutesFormat
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
        padding-top: 3px;
    }
</style>
