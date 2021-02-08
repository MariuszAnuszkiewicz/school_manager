<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col mt-5 bg-light">
                <div class="card-body"><h5><strong class="header-text">users review</strong></h5></div>
                <apexchart width="500" type="bar" :options="options" :series="series"></apexchart>
            </div>
        </div>
    </div>
</template>

<script>
import VueApexCharts from 'vue-apexcharts'
export default {
    components: {
        VueApexCharts
    },
    data() {
        return {
            pupils: [],
            teachers: [],
            options: {
                chart: {
                    id: 'vuechart-example'
                },
                xaxis: {
                    categories: ['pupils', 'teachers']
                },
                fill: {
                    colors: ['#838282']
                },
            },
        }
    },
    computed: {
        series: function() {
            return [{
                name: 'users review',
                data: [this.pupils[0], this.teachers[0]]
            }]
        }
    },
    methods: {
        loadChart() {
            axios.get('/admin/index').then(response => {
                this.pupils.push(response.data.pupils);
                this.teachers.push(response.data.teachers);
            });
        }
    },
    mounted() {
        this.loadChart();
    }
}
</script>

<style scoped>

    .header-text {
        color: #8f8f8f;
    }

</style>
