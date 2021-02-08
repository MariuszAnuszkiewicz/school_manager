<template>
    <div class="container">
        <div v-if="validateErrors.length > 0" class="validate-errors">
            <div v-for="validateError in validateErrors" class="error-explode">
                <p class="text-danger text-center"><b>{{ validateError }}</b></p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['input'],
    data() {
        return {
            response: {},
            validateErrors: [],
        }
    },
    methods: {
        validateRun(errorsResponse) {
            if (errorsResponse.status != 200) {
                this.response = errorsResponse.data.errors;
                for (let item in this.response){
                    for (let i = 0; i < this.response[item].length; i++) {
                        this.validateErrors.push(this.response[item][i]);
                        this.validateErrors.splice(this.validateErrors.length, this.validateErrors.length);
                    }
                }
            }
        },
        removeErrorRun() {
            if (this.input != '') {
                this.validateErrors.pop();
            }
        },
    },
}
</script>

<style scoped>


</style>
