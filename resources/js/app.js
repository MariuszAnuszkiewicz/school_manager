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
    Vue.component('my-grades', require('./components/pupil/my_grades/MyGrades.vue').default);
    Vue.component('pupil-navbar', require('./components/nav/PupilNavbar.vue').default);

/*********************************************************************************************************************/


// teacher zone \\
/*********************************************************************************************************************/

   Vue.component('pupils', require('./components/teacher/pupils/Pupils.vue').default);
   Vue.component('selected-pupils', require('./components/teacher/pupils/SelectedPupils.vue').default);
   Vue.component('send-message', require('./components/teacher/modals/SendMessage.vue').default);
   Vue.component('my-messages', require('./components/teacher/messages/MyMessages.vue').default);
   Vue.component('edit-message', require('./components/teacher/modals/EditMessage.vue').default);
   Vue.component('single-message', require('./components/teacher/messages/SingleMessage.vue').default);
   Vue.component('list-emails', require('./components/teacher/emails/ListEmails.vue').default);
   Vue.component('send-emails', require('./components/teacher/modals/SendEmail.vue').default);
   Vue.component('assign-rating', require('./components/teacher/pupils/ratings/AssignRating.vue').default);
   Vue.component('save-rating', require('./components/teacher/modals/SaveRating.vue').default);
   Vue.component('edit-rating', require('./components/teacher/modals/EditRating.vue').default);
   Vue.component('delete-rating', require('./components/teacher/modals/DeleteRating.vue').default);
   Vue.component('list-ratings', require('./components/teacher/pupils/ratings/ListRating.vue').default);
   Vue.component('detail-rating', require('./components/teacher/pupils/ratings/DetailRating.vue').default);
   Vue.component('teacher-navbar', require('./components/nav/TeacherNavbar.vue').default);


/*********************************************************************************************************************/


const app = new Vue({
    el: '#app',
});
