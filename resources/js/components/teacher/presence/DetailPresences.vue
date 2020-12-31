<template>
    <div class="container">
        <div class="row justify-content-center">
            <div :style="switchFlashStyle" class="flex flash-container">
                <div v-if="alerts !== undefined" v-for="alert in alerts" class="error-explode">
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
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="presence in presences.data">
                            <td class="text-center">{{ user.id }}</td>
                            <td class="text-center">{{ user.name }}</td>
                            <td class="text-center text-danger"><b>{{ presence.presence }}</b></td>
                            <td class="text-center">{{ presence.date }}</td>
                        </tr>
                    </tbody>
                </table>
                <pagination :data="presences" @pagination-change-page="getSources"></pagination>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            user: {},
            presences: {},
            alerts: [],
            switchFlashStyle: '',
            message: {
                warningText: "There aren't any presences for pupil.",
            },
            flashStyleWarning: {
                'display': 'none',
                show: {
                    'display': 'block',
                    'position': 'absolute',
                    'top': '225px',
                    'left': '43.5%',
                    'background-color': 'rgba(245, 34, 70, 0.3)',
                    'width': '275px',
                    'height': '35px',
                    'text-align': 'center',
                    'border-radius': '7px',
                }
            },
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
                this.presences = response.data.presences;
                this.showWarning();
            });
        },
        showWarning() {
            if (this.presences === null) {
                this.alerts.push(this.message.warningText);
                this.switchFlashStyle = this.flashStyleWarning.show;
            }
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

</style>
