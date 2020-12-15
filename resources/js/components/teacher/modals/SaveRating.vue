<template>
    <div class="overlay">
        <div class="saveRating">
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
                            <label id="label-semester"><strong><p class="semester-paragraph">Semester:</p></strong>
                                <select id="semester-values" v-model="semester">
                                    <option value="1">Semester 1</option>
                                    <option value="2">Semester 2</option>
                                </select>
                            </label>
                        </div>
                        <div class="form-group">
                            <label id="label-rating"><strong><p class="rating-paragraph">Rating Value:</p></strong>
                                <select id="rating-values" v-model="rating">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <slot name="footer"></slot>
                        <button type="button" id="save-rating" class="btn btn-primary" @click.once.prevent="ratingSave()">
                            Save Rating
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
    props: ['subjects', 'userId'],
    data() {
        return {
            semesters: {},
            semester: [],
            rating: [],
            confirm: false,
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
        }
    },
    methods: {
        ratingSave() {
            if (this.rating.length > 0) {
                axios.post('save-rating',
                    {
                        userId: this.userId,
                        rating: this.rating,
                        semester: this.semester,
                        subject: this.subjects[0].id,
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
    mounted() {
        this.ratingSave();
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
    .saveRating {
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
    }
    .flash-container {
        display: none;
    }
    .flash-container p {
        position: relative;
        top: 4px;
    }

</style>
