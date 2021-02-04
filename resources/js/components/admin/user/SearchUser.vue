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
                    <div v-if="validateErrors.length > 0" class="validate-errors">
                        <div v-for="validateError in validateErrors" class="error-explode">
                            <p class="text-danger text-center"><b>{{ validateError }}</b></p>
                        </div>
                    </div>
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
export default {
    components: {
        SearchResult,
    },
    data() {
        return {
            search: '',
            results: {},
            role: {},
            validateErrors: [],
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
                    if (this.search != '') {
                        _this.validateErrors.pop();
                    }
                });
                if (error.response.status != 200) {
                    for (let i = 0; i < error.response.data.errors.search.length; i++) {
                        _this.validateErrors.push(error.response.data.errors.search[i]);
                        _this.validateErrors.splice(1, _this.validateErrors.length);
                        _this.results = '';
                    }
                }
            });
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

    .validate-errors {
        position: relative;
        top: 5px;
        left: 0px;
    }

</style>