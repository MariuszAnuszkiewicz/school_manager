<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col mt-5">
                <div class="card-body"><h5><strong class="header-text">Search User</strong></h5></div>
                <form @submit.prevent="submitForm()" method="POST" ref="searchForm" class="form-horizontal">
                    <div id="search-bar" class="bg-warning text-center">
                        <div class="form-group">
                            <input type="text" name="search" v-model="search" placeholder="Search...">
                            <button id="submit-btn" class="btn btn-success ml-2 mt-1 mb-1" type="submit">Submit</button>
                        </div>
                    </div>
                    <validate :input="search" ref="validate"></validate>
                    <div v-if="results.length > 0" id="search-results">
                        <search-result :resultsData="results" :role="role"></search-result>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import SearchResult from "./SearchResult";
import Validate from "../../validation/Validate";
export default {
    components: {
        SearchResult,
        Validate,
    },
    data() {
        return {
            search: '',
            results: {},
            role: {},
        }
    },
    methods: {
        searchRun(formData) {
            let _this = this;
            axios.post('search-run', formData).then(response => {
                this.results = response.data;
                for (let item in this.results){
                    this.role = this.results[item].roles;
                }
            }).catch(function (error) {
                var submitBtn = document.getElementById('submit-btn');
                submitBtn.addEventListener('click', function () {
                   _this.removeError();
                });
                _this.validateInput(error.response);
                _this.results = '';
            });
        },
        validateInput(errorsResponse) {
            this.$refs.validate.validateRun(errorsResponse);
        },
        removeError() {
            this.$refs.validate.removeErrorRun();
        },
        submitForm() {
            var form = this.$refs.searchForm;
            let formData = new FormData(form);
            formData.append('search', this.search);
            this.searchRun(formData);
        }
    },
}
</script>

<style scoped>

    .header-text {
        color: #8f8f8f;
    }

</style>
