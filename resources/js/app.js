require('./bootstrap');

window.Vue = require('vue');
import VueApexCharts from 'vue-apexcharts'
Vue.use(VueApexCharts)

Vue.component('apexchart', VueApexCharts);
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('validate', require('./components/validation/Validate.vue'));

// user profile zone \\
/*********************************************************************************************************************/

    Vue.component('user-profile', require('./components/user_profile/UserProfile.vue').default);
    Vue.component('my-edit-profile', require('./components/user_profile/modals/MyEditProfile.vue').default);

/*********************************************************************************************************************/


// pupil zone \\
/*********************************************************************************************************************/

    Vue.component('pupil-navbar', require('./components/nav/PupilNavbar.vue').default);
    Vue.component('events', require('./components/pupil/events/Events.vue').default);
    Vue.component('messages', require('./components/pupil/messages/Messages.vue').default);
    Vue.component('single-message-pupil', require('./components/pupil/messages/SingleMessage.vue').default);
    Vue.component('lesson-plan', require('./components/pupil/lesson_plan/LessonPlan.vue').default);
    Vue.component('my-teachers', require('./components/pupil/my_teachers/MyTeachers.vue').default);
    Vue.component('my-teachers-modal', require('./components/pupil/modals/MyTeachersModal.vue').default);
    Vue.component('my-grades', require('./components/pupil/my_grades/MyGrades.vue').default);

/*********************************************************************************************************************/


// teacher zone \\
/*********************************************************************************************************************/

   Vue.component('teacher-navbar', require('./components/nav/TeacherNavbar.vue').default);
   Vue.component('pupils', require('./components/teacher/pupils/Pupils.vue').default);
   Vue.component('selected-pupils', require('./components/teacher/pupils/SelectedPupils.vue').default);
   Vue.component('send-message', require('./components/teacher/modals/SendMessage.vue').default);
   Vue.component('my-messages', require('./components/teacher/messages/MyMessages.vue').default);
   Vue.component('edit-message', require('./components/teacher/modals/EditMessage.vue').default);
   Vue.component('single-message-teacher', require('./components/teacher/messages/SingleMessage.vue').default);
   Vue.component('list-emails', require('./components/teacher/emails/ListEmails.vue').default);
   Vue.component('send-emails', require('./components/teacher/modals/SendEmail.vue').default);
   Vue.component('assign-rating', require('./components/teacher/pupils/ratings/AssignRating.vue').default);
   Vue.component('save-rating', require('./components/teacher/modals/SaveRating.vue').default);
   Vue.component('edit-rating', require('./components/teacher/modals/EditRating.vue').default);
   Vue.component('delete-rating', require('./components/teacher/modals/DeleteRating.vue').default);
   Vue.component('list-ratings', require('./components/teacher/pupils/ratings/ListRating.vue').default);
   Vue.component('detail-rating', require('./components/teacher/pupils/ratings/DetailRating.vue').default);
   Vue.component('fill-presence', require('./components/teacher/presence/FillPresence.vue').default);
   Vue.component('detail-presences', require('./components/teacher/presence/DetailPresences.vue').default);
   Vue.component('edit-presence', require('./components/teacher/modals/EditPresence.vue').default);
   Vue.component('create-event', require('./components/teacher/event/CreateEvent.vue').default);
   Vue.component('save-event', require('./components/teacher/modals/SaveEvent.vue').default);
   Vue.component('list-events', require('./components/teacher/event/ListEvent.vue').default);
   Vue.component('edit-event', require('./components/teacher/modals/EditEvent.vue').default);

/*********************************************************************************************************************/


// admin zone \\
/*********************************************************************************************************************/

   Vue.component('index-chart', require('./components/admin/Chart.vue').default);
   Vue.component('admin-navbar', require('./components/nav/AdminNavbar.vue').default);
   Vue.component('subject-teacher', require('./components/admin/SubjectTeacher.vue').default);
   Vue.component('search-user', require('./components/admin/user/SearchUser.vue').default);
   Vue.component('search-result', require('./components/admin/user/SearchResult.vue').default);
   Vue.component('create-lesson-plan', require('./components/admin/lesson_plan/CreateLessonPlan.vue').default);
   Vue.component('new-lesson-plan', require('./components/admin/lesson_plan/NewLessonPlan.vue').default);


/*********************************************************************************************************************/


const app = new Vue({
    el: '#app',
});
