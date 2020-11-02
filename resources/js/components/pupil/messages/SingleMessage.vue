<template>
    <div class="container">
        <div class="container col-md-10 mt-5">
            <div class="card-body"><h5><strong class="header-text">Message from Teacher</strong></h5></div>
            <div class="row text-center">
                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="mb-2">
                        <strong>Id:</strong>
                    </div>
                    <div class="form-group bg-light pt-2 pb-2">
                        <strong>{{ messages.message.id }}</strong>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="mb-2">
                        <strong>From:</strong>
                    </div>
                    <div class="form-group bg-light pt-2 pb-2">
                        <p class="mt-3">{{ teacher }}</p>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="mb-2">
                        <strong>Message:</strong>
                    </div>
                    <div class="form-group bg-light pt-2 pb-2">
                        <p class="mt-3">{{ messages.message.message}}</p>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12">
                    <div class="mb-2">
                        <strong>Create Date:</strong>
                    </div>
                    <div class="form-group bg-light pt-2 pb-2">
                        <p class="mt-3">{{ new Date(messages.message.created_at).toLocaleString('pl-PL') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            messages: {},
            teacher: {},
        }
    },

    methods: {
        getMessage() {
            let id = window.location.href.split('/').pop();
            axios.get('/pupil/messages/' + id).then(response => {
                this.messages = response.data
                this.teacher = response.data.teacher
            });
        },
    },
    mounted() {
        this.getMessage()
    },
}
</script>

<style scoped>
button {
    font-size: 12px;
}
.header-text {
    color: #8f8f8f;
}

</style>
