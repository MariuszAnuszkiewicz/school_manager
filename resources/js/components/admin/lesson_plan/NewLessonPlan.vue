<template>
    <div class="row justify-content-center">
        <div v-if="messagesInfo !== undefined" :style="{ display: showMessageInfo }" class="flex flash-container flash-style-info">
            <div v-for="messageInfo in messagesInfo" class="error-explode">
                <p>{{ messageInfo }}</p>
            </div>
        </div>
        <div class="col mt-5 mb-2">
            <div class="col mt-5 pb-3">
                <button class="btn btn-primary" @click="createNewLessonPlan">New Lesson Plan</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            messagesInfo: [],
            showMessageInfo: 'none',
        }
    },
    methods: {
        createNewLessonPlan() {
            let id = window.location.href.split('/').pop();
            axios.post('/admin/save-lesson-plan', {
                class_in_school_id : id,
            }).then(response => {
                this.showInfo(response.data.message);
            }).catch(function (error) {
                console.log(error.response.data)
            });
        },
        showInfo(infoText) {
            if (this.messagesInfo !== null) {
                this.messagesInfo.push(infoText);
                this.messagesInfo.splice(1, this.messagesInfo.length);
                this.showMessageInfo = 'block';
            }
        },
    },
}
</script>

<style scoped>

    .flash-container p {
        position: relative;
        top: 5px;
    }
    .flash-style-info {
        display: block;
        position: absolute;
        top: 315px;
        left: 42.2%;
        background-color: rgba(60, 204, 102, 0.3);
        width: 333px;
        height: 35px;
        text-align: center;
        border-radius: 7px;
        margin: 0px 0px 15px 0px;
    }

</style>