<template>
    <div class="overlay">
        <div class="deleteRating">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <slot name="header"></slot>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label id="label-pupil"><strong><p class="pupil-paragraph">User Id:</p></strong>
                                <input type="text" id="pupil-id" v-model="userId"/>
                            </label>
                        </div>
                        <div class="form-group">
                            <label id="label-subject"><strong><p class="subject-paragraph">Subject:</p></strong>
                                <input type="text" id="subject" v-for="subject in subjects" v-model="subject.name"/>
                            </label>
                        </div>
                        <div class="form-group">
                            <div v-if="this.ratings !== undefined" id="delete-rating">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <td class="text-left"><strong>select</strong></td>
                                            <td class="text-center"><strong>rating</strong></td>
                                            <td class="text-center pl-4"><strong>create</strong></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="bg-light" v-for="(rating, index) in ratings">
                                            <td class="text-danger">
                                                <input type="checkbox" class="rating-select ml-2 mt-3"
                                                       v-model="selected"
                                                       :value="rating + '|' + createAt[index]">
                                            </td>
                                            <td>
                                                <strong class="text-danger">
                                                   <p class="p-rating text-left pt-3 pl-3" :data-rating="rating">{{ rating }}</p>
                                                </strong>
                                            </td>
                                            <td>
                                                <p class="text-center pt-3 pl-3">
                                                    {{ createAt[index] | formatDate(createAt[index]) }}
                                                </p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div v-else-if="this.ratings === undefined">
                                <div class="flex flash-container flash-style-warning">
                                    <div v-for="messageWarning in messagesWarning" class="error-explode">
                                        <p>{{ messageWarning }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <slot name="footer"></slot>
                        <button type="button" id="save-rating" class="btn btn-primary" @click.once.prevent="deletePupilRating()">
                            Delete Rating
                        </button>
                    </div>
                    <div class="flash-wrapper">
                        <div v-if="this.confirm === true" class="flex flash-container flash-style-info">
                            <div v-for="messageInfo in messagesInfo" class="error-explode">
                                <p>{{ messageInfo }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['subjects', 'userId', 'ratings', 'createAt', 'errors'],
    data() {
        return {
            semesters: {},
            semester: [],
            selected: [],
            confirm: false,
            onCheckbox: false,
            messagesInfo: [],
            messagesWarning: [],
            showMessageInfo: 'none',
            showMessageWarning: 'none',
        }
    },
    methods: {
        deletePupilRating() {
            if (this.selected.length > 0) {
                axios.post('delete-pupil-rating',
                    {
                        userId: this.userId,
                        rating: this.selected,
                    }
                ).then(response => {
                    this.showInfo(response.data.message);
                }).catch(function (error) {
                    console.log(error.response.data)
                });
            }
        },
        showInfo(infoText) {
            if (infoText !== null) {
                this.messagesInfo.push(infoText);
                this.messagesInfo.splice(1, this.messagesInfo.length);
                this.showMessageInfo = 'block';
                this.confirm = true;
            }
        },
        showWarning(warningText) {
            if (warningText !== null) {
                this.messagesWarning.push(warningText);
                this.messagesWarning.splice(1, this.messagesWarning.length);
                this.showMessageWarning = 'block';
            }
        },
    },
    filters: {
        formatDate(value) {
            var date = new Date(value);
            let timestamps = '';
            let minutesFormat = '';

            if (date.getMinutes() < 10) {
                minutesFormat += "0" + date.getMinutes();
            } else {
                minutesFormat += date.getMinutes();
            }

            if (value === null) {
                return timestamps = "";
            } else {
                return timestamps +=
                    " " + date.getFullYear() +
                    "-" + (date.getMonth() + 1) +
                    "-" + date.getDate() +
                    " " + date.getHours() +
                    ":" + minutesFormat;
            }
        }
    },
    mounted() {
        this.showWarning("There aren\'t any ratings for pupils");
    },
}
</script>

<style scoped>
    .overlay {
        position: absolute;
        top: 200px;
        left: 24%;
        width: 52.1%;
        height: 75%;
        background-color: rgba(0, 0, 0, 0.8);
        z-index: 5;
    }
    .deleteRating {
        display: table;
        position: relative;
        top: 65px;
        left: 33%;
        width: 400px;
        height: 300px;
        background-color: #4c6fb1;
        z-index: 9999;
        padding: 10px 10px 10px 10px;
        transition: opacity .3s ease;
    }
    .rating-select {
        position: relative;
        top: 3px;
        left: 0px;
        width: 18px;
        height: 18px;
        background-color: #fffed5;
    }
    #delete-rating {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        border-radius: 10px;
        background-color: #F5F5F5;
        overflow-y: scroll;
        width: 350px;
        height: 175px;
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
    .flash-style-info {
        display: block;
        position: relative;
        top: 2px;
        left: 25px;
        background-color: rgba(60, 204, 102, 0.3);
        width: 333px;
        height: 35px;
        text-align: center;
        border-radius: 7px;
    }
    .flash-style-warning {
        display: block;
        position: relative;
        top: 2px;
        left: 5px;
        background-color: rgba(245, 34, 70, 0.3);
        width: 333px;
        height: 35px;
        text-align: center;
        border-radius: 7px;
        padding-bottom: 10px;
    }

</style>
