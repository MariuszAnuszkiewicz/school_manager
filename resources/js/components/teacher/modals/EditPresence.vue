<template>
    <transition name="modal">
        <div class="overlay">
            <div class="editPresence">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                          <slot name="header"></slot>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label id="label-presence"><strong><p class="presence-paragraph">Status Presence:</p></strong>
                                    <select id="presences-values" v-model="presence">
                                        <option value="yes">present</option>
                                        <option value="no">absent</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <slot name="footer"></slot>
                            <button type="button" id="update-presence" class="btn btn-primary" @click="updatePresence">
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
    props: ['presenceData'],
    data() {
        return {
            id: this.presenceData.id,
            presence: this.presenceData.presence,
            confirm: false,
            flashTextInfo: '',
        }
    },
    methods: {
        updatePresence() {
            axios.put('/teacher/detail-presence/update', {
                id: this.id,
                presence: this.presence,
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
        left: 24%;
        top: 220px;
        width: 52%;
        height: 65%;
        background-color: rgba(0, 0, 0, 0.8);
        z-index: 5;
    }
    .editPresence {
        display: table;
        position: relative;
        top: 25%;
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