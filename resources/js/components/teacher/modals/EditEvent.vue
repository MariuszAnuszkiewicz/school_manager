<template>
    <transition name="modal">
        <div class="overlay">
            <div class="editEvent">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <slot name="header"></slot>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label id="label-event"><strong><p class="event-paragraph">Title Event:</p></strong>
                                    <textarea id="title" name="title" v-model="title" rows="5" cols="35"></textarea>
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <slot name="footer"></slot>
                            <button type="button" id="update-event" class="btn btn-primary" @click.once="updateEvent()">
                                Update
                            </button>
                        </div>
                        <div class="flash-wrapper">
                            <div v-if="this.confirm === true" class="flex flash-container flash-style-info">
                               <p>{{ flashTextInfo }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    props: ['eventData'],
    data() {
        return {
            id: this.eventData.id,
            title: this.eventData.title,
            confirm: false,
            flashTextInfo: '',
        }
    },
    methods: {
        updateEvent() {
            axios.put('update-event/' + this.id, {
               id: this.id,
               title: this.title
            }).then(response => {
               this.flashTextInfo = response.data.message;
               this.confirm = true;
            }).catch(function (error) {
               console.log(error.response.data)
            });
        },
    },
}
</script>

<style scoped>

    .overlay {
        position: absolute;
        top: 0px;
        width: 97.5%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.8);
        z-index: 5;
    }
    .editEvent {
        display: table;
        position: relative;
        top: 45px;
        left: 31.5%;
        width: 400px;
        height: 300px;
        background-color: #4c6fb1;
        z-index: 9999;
        padding: 10px 10px 10px 10px;
        transition: opacity .3s ease;
    }
    .flash-wrapper {
        margin-bottom: 15px;
    }
    .flash-container {
        display: none;
    }
    .flash-container p {
        position: relative;
        top: 5px;
    }
    .flash-style-info {
        display: block;
        position: relative;
        top: 2px;
        left: 22px;
        background-color: rgba(60, 204, 102, 0.3);
        width: 333px;
        height: 35px;
        text-align: center;
        border-radius: 7px;
    }

</style>