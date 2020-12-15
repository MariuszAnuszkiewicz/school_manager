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
                            <div v-if="this.ratings !== undefined" id="delete-rating" :style="{
                                   'overflow-y': 'scroll',
                                   'width': '350px',
                                   'height': '175px',
                                }">
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
                                                <input type="checkbox" class="rating-select ml-2 mt-2"
                                                       v-model="selected"
                                                       :value="rating + '|' + create[index]"
                                                       >
                                            </td>
                                            <td>
                                                <strong class="text-danger"><p class="p-rating text-left pt-2 pl-3" :data-rating="rating">{{ rating }}</p></strong>
                                            </td>
                                            <td>
                                                <p class="text-center pt-2 pl-3">
                                                    {{ create[index] | formatDate(create[index]) }}
                                                </p>
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
                    </div>
                    <div class="modal-footer">
                        <slot name="footer"></slot>
                        <button type="button" id="save-rating" class="btn btn-primary" @click.once.prevent="ratingDelete()">
                            Delete Rating
                        </button>
                    </div>
                    <div class="flash-wrapper">
                        <div v-if="this.confirm === true" :style="flashStyleInfo.show" class="flex flash-container">
                            <p>{{ flashText }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['subjects', 'userId', 'ratings', 'create', 'errors'],
    data() {
        return {
            semesters: {},
            semester: [],
            selected: [],
            confirm: false,
            onCheckbox: false,
            flashText: '',
            flashStyleInfo: {
                'display': 'none',
                show: {
                    'display': 'block',
                    'position': 'relative',
                    'top': '2px',
                    'left': '22px',
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
        ratingDelete() {
            if (this.selected.length > 0) {
                axios.post('delete-rating',
                    {
                        userId: this.userId,
                        rating: this.selected,
                        subject: this.subjects,
                    }
                ).then(response => {
                    this.flashText = response.data.message;
                    this.confirm = true;
                }).catch(function (error) {
                    console.log(error.response.data)
                });
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
        this.ratingDelete();
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
