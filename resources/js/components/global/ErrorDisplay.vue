<template>
    <div v-if="this.errors.length > 0 && this.confirm === false" :style="{display: 'block'}" class="errors-wrapper">
        <div v-if="this.errors.length > 0 && this.confirm === false" :style="{display: 'block'}" class="error-container">
            <p v-for="error in showErrors()" :key="error.id">{{ error }}</p>
        </div>
    </div>
</template>

<script>
export default {
    props: ['errors', 'confirm'],
    data() {
        return {
           error: false,
        }
    },
    methods: {
        showErrors() {
            return this.errors;
        },
    },
    mounted() {
        this.showErrors()
    }
}
</script>

<style scoped>
    .errors-wrapper {
        display: none;
        position: relative;
        top: 30px;
        left: 29.6%;
        background-color: rgba(245, 34, 70, 0.3);
        width: 450px;
        height: auto;
        text-align: center;
        border-radius: 7px;
    }
    .error-container {
        display: none;
        position: relative;
        top: 7px;
    }
    .error-container p {
        display: inline-block;
        padding-top: 4px;
    }
</style>
