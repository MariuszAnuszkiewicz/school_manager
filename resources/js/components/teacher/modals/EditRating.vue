<template>
    <div class="overlay">
        <div class="editRating">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form method="POST" ref="ratingForm" class="form-horizontal">
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
                                <div v-if="this.ratings !== undefined" id="edit-rating" :style="{
                                   'overflow-y': 'scroll',
                                   'width': '350px',
                                   'height': '175px',
                                }">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <td class="text-left"><strong>rating</strong></td>
                                                <td class="text-center"><strong>create</strong></td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="bg-light" v-for="(rating, index) in ratings">
                                                <td class="text-danger" @click.once.prevent="onRatingOptions($event, index)">
                                                    <div v-if="onOptions === false">
                                                        <strong>
                                                            <p class="p-rating text-left pt-3 pl-3"
                                                               :data-rating="rating"
                                                               :data-create="createAt">{{ rating }}
                                                            </p>
                                                        </strong>
                                                    </div>
                                                    <div v-else-if="onOptions === true && dataRating === rating && dataIndex === index">
                                                        <label id="label-rating"><strong><p class="text-black-50 rating-paragraph">Rating Value:</p></strong>
                                                            <select id="rating-values" v-model="editRating">
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5">5</option>
                                                                <option value="6">6</option>
                                                            </select>
                                                        </label>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div v-if="onOptions === true && dataIndex === index">
                                                        <p class="text-center pt-3 pl-3">
                                                            {{ createAt[index] | formatDate(createAt[index]) }}
                                                        </p>
                                                    </div>
                                                    <div v-if="onOptions === false">
                                                        <p class="text-center pt-3 pl-3">
                                                            {{ createAt[index] | formatDate(createAt[index]) }}
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div v-else-if="this.ratings === undefined"><p class="text-center pt-1"
                                                                               :style="flashStyleWarning.show"
                                                                               v-for="error in errors">{{ error }}
                                                                            </p>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <slot name="footer"></slot>
                                <button type="button" id="update-rating" class="btn btn-primary" @click.once.prevent="pupilRatingUpdate()">
                                    Update Rating
                                </button>
                            </div>
                            <div class="flash-wrapper">
                                <div v-if="this.confirm === true" :style="flashStyleInfo.show" class="flex flash-container">
                                    <p>{{ flashText }}</p>
                                </div>
                            </div>
                        </div>
                    </form>
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
            confirm: false,
            onOptions: false,
            editRating: [],
            dataRating: '',
            dataCreate: '',
            dataIndex: '',
            flashText: '',
            flashStyleInfo: {
                'display': 'none',
                show: {
                    'display': 'block',
                    'position': 'relative',
                    'top': '2px',
                    'left': '9px',
                    'background-color': 'rgba(60, 204, 102, 0.3)',
                    'width': '333px',
                    'height': '35px',
                    'text-align': 'center',
                    'border-radius': '7px',
                    'padding-bottom': '10px',
                }
            },
            flashStyleWarning: {
                'display': 'none',
                show: {
                    'display': 'block',
                    'position': 'relative',
                    'top': '2px',
                    'left': '5px',
                    'background-color': 'rgba(245, 34, 70, 0.3)',
                    'width': '333px',
                    'height': '35px',
                    'text-align': 'center',
                    'border-radius': '7px',
                    'padding-bottom': '10px',
                }
            },
        }
    },
    methods: {
        pupilRatingUpdate() {
            var form = this.$refs.ratingForm;
            let formData = new FormData(form);
            formData.append('dataRating', this.dataRating);
            formData.append('dataCreate', this.dataCreate);
            formData.append('rating', this.editRating);
            formData.append('userId', this.userId);
            axios.post('update-pupil-rating', formData).then(response => {
                this.flashText = response.data.message;
                this.confirm = true;
            }).catch(function (error) {
                console.log(error.response.data)
            });
        },
        onRatingOptions(event, index) {
            this.onOptions = true;
            this.dataRating = event.target.getAttribute('data-rating');
            this.dataCreate = event.target.getAttribute('data-create').split(',')[index];
            this.editRating = this.dataRating;
            this.dataIndex = index;
        }
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
    .editRating {
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
    .flash-wrapper {
        margin-bottom: 15px;
        position: relative;
        top: 5px;
        left: 0px;
    }
    .flash-container {
        display: none;
    }
    .flash-container p {
        position: relative;
        top: 4px;
    }
    #edit-rating {
        -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
        border-radius: 10px;
        background-color: #F5F5F5;
    }
    .p-rating {
        cursor: pointer;
    }
</style>
