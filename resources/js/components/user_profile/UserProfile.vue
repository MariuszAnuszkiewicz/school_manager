<template>
    <div class="container">
        <div class="row justify-content-center bg-light pb-5">
            <div class="col mt-5">
                <div class="card">
                    <div class="card-body col-md-12 mt-3">
                        <strong>User Profile: </strong><p class="paragraph-profile"><b>{{ user.name }}</b></p>
                        <div class="user-type"><strong>User Type: </strong>
                           <p class="paragraph-profile"><b>{{ userRole }}</b></p>
                        </div>
                    </div>
                </div>
                <div class="profile-header-container">
                    <div class="profile-header-img">
                       <div v-if="this.showPreview === true">
                          <img v-bind:src="imagePreview" class="rounded-circle" v-show="showPreview">
                       </div>
                       <div v-if="this.showPreview === false">
                          <img v-bind:src="'/images/user/avatars/' + subPath + user.avatar" class="rounded-circle">
                       </div>
                    </div>
                </div>
                <div id="upload-wrapper" class="large-12 medium-12 small-12 cell">
                    <form @submit.prevent="submitForm()" id="uploadForm" enctype="multipart/form-data">
                        <label id="label-upload"><strong><p class="text-center pt-2"><i class="fas fa-folder-open"></i></p></strong>
                            <input type="file" name="picture" class="btn btn-light form-control-file" id="inputPicture"
                                   v-on:change="handleFileUpload($event)">
                        </label>
                        <button class="upload-btn btn btn-success" id="uploadConfirm" v-on:click.stop.once="uploadConfirm()">Upload Image</button>
                    </form>
                    <div class="edit-btn-wrapper mt-2">
                        <button id="edit-profile-btn" class="btn btn-blue" @click="openModal()">
                            <i class="fas fa-edit"></i>
                        </button>
                    </div>
                </div>
                <div v-if="this.confirm === true" class="flex flash-container flash-style-info">
                    <div v-for="messageInfo in messagesInfo" class="error-explode">
                        <p>{{ messageInfo }}</p>
                    </div>
                </div>
            </div>
        </div>
        <my-edit-profile :user="user" v-if="showModal === true">
            <h3 slot="header" class="modal-title">
                Edit User Profile
            </h3>
            <div slot="footer">
                <button type="button" class="btn btn-outline-info" @click="closeModal">Close</button>
            </div>
        </my-edit-profile>
    </div>
</template>

<script>
import MyEditProfile from "./modals/MyEditProfile";
export default {
    components: {
        MyEditProfile,
    },
    data() {
        return {
            user: {},
            userRole: {},
            picture: '',
            subPath: '',
            imagePreview: '',
            showModal: false,
            showPreview: false,
            confirm: false,
            messagesInfo: [],
            showMessageInfo: 'none',
            message: {
                text: 'Your avatar has been updated successfully.',
            },
        }
    },
    methods: {
        getUser() {
            let id = window.location.href.split('/').pop();
            axios.get('/user_profile/user/' + id).then(response => {
                this.user = response.data.user
                this.userRole = response.data.userRole
                if (this.user.avatar === 'user-avatar.png') {
                    this.subPath = 'default/';
                } else {
                    this.subPath = '';
                }
            });
        },
        handleFileUpload(e) {
            this.picture = e.target.files[0];
            let reader  = new FileReader();
            reader.addEventListener("load", function() {
                this.showPreview = true;
                this.imagePreview = reader.result;
            }.bind(this), false);
            if (this.picture) {
                if (/\.(jpe?g|png|gif)$/i.test(this.picture.name)) {
                    reader.readAsDataURL(this.picture);
                }
            }
        },
        submitForm() {
            let _this = this;
            let formData = new FormData(document.getElementById('uploadForm'));
            formData.append('picture', document.querySelector('#inputPicture').files[0]);
            axios.post('/user_profile/user', formData).then(response => {
                if (_this.picture.name !== undefined) {
                   _this.showInfo(_this.message.text);
                }
            }).catch(function (error) {
                console.log(error);
            });
        },
        showInfo(infoText) {
            if (infoText !== null) {
                this.messagesInfo.push(infoText);
                this.messagesInfo.splice(1, this.messagesInfo.length);
                this.showMessageInfo = 'block';
                this.confirm = true;
            }
        },
        uploadConfirm() {
            if (this.imagePreview !== '') {
                this.confirm = true;
            }
        },
        openModal() {
            this.showModal = true;
        },
        closeModal() {
            setTimeout(() => {
               this.showModal = false;
            }, 250);
        },
    },
    mounted() {
        this.uploadConfirm()
        this.submitForm()
        this.getUser()
    },
}
</script>

<style scoped>

    .profile-header-container {
        position: relative;
        top: 15px;
        left: 38.7%;
        display: block;
        width: 250px;
        height: 250px;
        background-color: #4c6fb1;
        border-radius: 5px;
    }
    .profile-header-img {
        position: relative;
        top: 13px;
        left: 13px;
        display: block;
        width: 225px;
        height: 225px;
        padding: 10px 10px 10px 10px;
        background-color: #F8F9FA;
        border-radius: 125px;
    }
    .rounded-circle {
        position: relative;
        top: 40px;
        left: 40px;
        display: block;
        width: 125px;
        height: 125px;
        z-index: 99;
    }
    p.paragraph-profile {
        color: #4c6fb1;
        display: inline-block;
    }
    .user-type {
        float: right;
        display: inline;
    }
    strong {
        color: #8f8f8f;
    }
    .card {
        border-radius: 5px;
    }
    #upload-wrapper {
        position: relative;
        top: 25px;
        left: 38.5%;
        width: 285px;
        height: 150px;
        overflow: hidden;
        display: inline-block;
        border-radius: 5px;
    }
    #label-upload {
        position: relative;
        top: 0px;
        left: 10%;
        width: 195px;
        height: 40px;
        background-color: #dddcd2;
        border-radius: 5px;
        cursor: pointer;
        display: inline-block;
    }
    input[type="file"] {
        display: none;
    }
    .upload-btn {
        position: relative;
        top: 0px;
        left: 22%;
    }
    .fas {
        font-size: 25px;
    }
    .flash-wrapper {
        position: relative;
        top: 20px;
    }
    .flash-container {
        display: none;
    }
    .flash-container p {
        position: relative;
        top: 4px;
    }
    #edit-profile-btn {
        display: block;
        position: relative;
        top: 0px;
        left: 100px;
        width: 55px;
        height: 55px;
        border-radius: 50%;
    }
    .btn-blue {
        background-color: #4c6fb1;
        color: white;
    }
    .flash-style-info {
        display: block;
        position: relative;
        top: 35px;
        left: 34.2%;
        background-color: rgba(60, 204, 102, 0.3);
        width: 350px;
        height: 35px;
        text-align: center;
        border-radius: 7px;
    }

</style>
