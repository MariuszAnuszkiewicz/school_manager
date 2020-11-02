require('./bootstrap');

window.Vue = require('vue');

Vue.component('pagination', require('laravel-vue-pagination'));

// pupil zone \\
/*********************************************************************************************************************/

    Vue.component('events', require('./components/events/Events.vue').default);
    Vue.component('messages', require('./components/messages/Messages.vue').default);
    Vue.component('single-message', require('./components/messages/SingleMessage.vue').default);
    Vue.component('lesson-plan', require('./components/lesson_plan/LessonPlan.vue').default);
    Vue.component('my-teachers', require('./components/pupil/my_teachers/MyTeachers.vue').default);
    Vue.component('my-teachers-modal', require('./components/pupil/modals/MyTeachersModal.vue').default);
    
/*********************************************************************************************************************/
const app = new Vue({
    el: '#app',
});
