<template>
    <transition name="modal">
        <div class="myEditProfile">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <slot name="header"></slot>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label id="label-name"><strong><p class="paragraph-name">Name:</p></strong>
                               <input type="text" v-model="user.name" id="name" class="ml-2" size="40">
                            </label>
                        </div>
                        <div class="form-group">
                            <label id="label-email"><strong><p class="paragraph-email">Email:</p></strong>
                               <input type="text" v-model="user.email" id="email" class="ml-2" size="40">
                            </label>
                        </div>
                        <div class="form-group">
                            <label id="label-phone"><strong><p class="paragraph-phone">Phone:</p></strong>
                               <input type="text" v-model="user.phone" id="phone" class="ml-2" size="40">
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <slot name="footer"></slot>
                        <button type="button" id="personal-data-btn" class="btn btn-primary" @click.once="userUpdate()">
                            Update
                        </button>
                    </div>
                    <div v-if="this.confirm === true" class="flex flash-container flash-style-info">
                        <div v-for="messageInfo in messagesInfo" class="error-explode">
                            <p>{{ messageInfo }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    props: ['user'],
    data() {
        return {
            name: '',
            email: '',
            phone: '',
            confirm: false,
            messagesInfo: [],
        }
    },
    methods: {
        userUpdate() {
            let _this = this;
            let id = window.location.href.split('/').pop();
            axios.put('/user_profile/user/' + id, {
                name: this.user.name,
                email: this.user.email,
                phone: this.user.phone,
            }).then(function (response) {
                _this.confirmUpdate(response.data.message);
            }).catch(function (error) {
                console.log(error);
            });
        },
        confirmUpdate: function(response) {
            let updateBtn = document.getElementById("personal-data-btn");
            updateBtn.addEventListener('click', function () {
                this.showInfo(response);
            }.bind(this), false);
        },
        showInfo(infoText) {
            if (infoText !== null) {
                this.messagesInfo.push(infoText);
                this.messagesInfo.splice(1, this.messagesInfo.length);
                this.confirm = true;
            }
        },
    },
    mounted() {
        this.userUpdate();
    }
}
</script>

<style scoped>
    .myEditProfile {
        display: table;
        position: absolute;
        top: 240px;
        left: 40.2%;
        width: 400px;
        height: 300px;
        background-color: #4c6fb1;
        z-index: 9999;
        padding: 10px 10px 10px 10px;
        transition: opacity .3s ease;
    }
    .paragraph-name {
        padding-left: 10px;
    }
    .paragraph-email {
        padding-left: 10px;
    }
    .paragraph-phone {
        padding-left: 10px;
    }
    .flash-container {
        display: none;
    }
    .flash-container p {
        position: relative;
        top: 4px;
    }
    .flash-style-info {
        display: block;
        position: relative;
        top: 2px;
        left: 9px;
        background-color: rgba(60, 204, 102, 0.3);
        width: 375px;
        height: 35px;
        text-align: center;
        border-radius: 7px;
        margin: 0px 0px 15px 0px;
    }

</style>
