require('./bootstrap');

window.Vue = require('vue');

Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('error-display', require('./components/global/ErrorDisplay.vue').default);

// user profile zone \\
/*********************************************************************************************************************/

    Vue.component('user-profile', require('./components/user_profile/UserProfile.vue').default);
    Vue.component('my-edit-profile', require('./components/user_profile/modals/MyEditProfile.vue').default);

/*********************************************************************************************************************/


// pupil zone \\
/*********************************************************************************************************************/

    Vue.component('events', require('./components/pupil/events/Events.vue').default);
    Vue.component('messages', require('./components/pupil/messages/Messages.vue').default);
    Vue.component('single-message', require('./components/pupil/messages/SingleMessage.vue').default);
    Vue.component('lesson-plan', require('./components/pupil/lesson_plan/LessonPlan.vue').default);
    Vue.component('my-teachers', require('./components/pupil/my_teachers/MyTeachers.vue').default);
    Vue.component('my-teachers-modal', require('./components/pupil/modals/MyTeachersModal.vue').default);

/*********************************************************************************************************************/

const app = new Vue({
    el: '#app',
});
