require('./bootstrap');

window.Vue = require('vue');

Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('events', require('./components/events/Events.vue').default);

const app = new Vue({
    el: '#app',
});
