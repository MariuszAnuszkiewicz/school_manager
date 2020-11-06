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
                            <small >{{ error_name }} </small>
                        </div>
                        <div class="form-group">
                            <label id="label-email"><strong><p class="paragraph-email">Email:</p></strong>
                               <input type="text" v-model="user.email" id="email" class="ml-2" size="40">
                            </label>
                            <small >{{ error_email }} </small>
                        </div>
                        <div class="form-group">
                            <label id="label-phone"><strong><p class="paragraph-phone">Phone:</p></strong>
                               <input type="text" v-model="user.phone" id="phone" class="ml-2" size="40">
                            </label>
                            <small >{{ error_phone }} </small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <slot name="footer"></slot>
                        <button type="button" id="update" class="btn btn-primary" @click.stop="userUpdate()">
                            Update
                        </button>
                    </div>
                    <div class="flash-wrapper">
                        <div v-if="this.confirm === true" :style="flashStyle.show" class="flex flash-container">
                            <p>{{ message.text }}</p>
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
            error_name : '',
            error_email : '',
            error_phone: '',
            message: {
                text: 'Your personal data has been updated successfully.',
            },
            flashStyle: {
                'display': 'none',
                show: {
                    'display': 'block',
                    'position': 'relative',
                    'top': '2px',
                    'left': '9px',
                    'background-color': 'rgba(60, 204, 102, 0.3)',
                    'width': '375px',
                    'height': '35px',
                    'text-align': 'center',
                    'border-radius': '7px',
                    'padding-bottom': '10px',
                }
            },
        }
    },
    methods: {
        userUpdate() {
            let id = window.location.href.split('/').pop();
                axios.put('/user_profile/user/' + id, {
                    name: this.user.name,
                    email: this.user.email,
                    phone: this.user.phone,
                }).then(function (response) {

                }).catch(function (error) {
                   
                });
                this.confirmUpdate();
        },
        confirmUpdate: function(){
            let updateBtn = document.getElementById("update");
            updateBtn.addEventListener('click', function () {
                this.confirm = true;
            }.bind(this), false);
        },
    },
    mounted() {
        this.userUpdate()
    }
}
</script>

<style scoped>
    .myEditProfile {
        display: table;
        position: absolute;
        top: 240px;
        left: 38.5%;
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
    .flash-wrapper {
        margin-bottom: 15px;
    }
    .flash-container {
        display: none;
    }
    .flash-container p {
        position: relative;
        top: 4px;
    }

</style>
