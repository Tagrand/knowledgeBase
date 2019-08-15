import Vue from 'vue';
import Vuex from 'vuex'
import App from '@/js/views/App';
import Routes from '@/js/routes.js';

Vue.use(Vuex)


window._ = require('lodash');
window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

const app = new Vue({
    el: '#app',
    router: Routes,
    render: h => h(App),
});

export default app;
