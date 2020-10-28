require('./bootstrap');

window.Vue = require('vue');

Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('events', require('./components/events/Events.vue').default);
Vue.component('messages', require('./components/messages/Messages.vue').default);
Vue.component('single-message', require('./components/messages/SingleMessage.vue').default);

const app = new Vue({
    el: '#app',
});
