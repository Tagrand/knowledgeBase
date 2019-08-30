/* eslint-disable import/no-unresolved */

import Vue from 'vue';
import App from '@/js/views/App';
import Routes from '@/js/routes';
import store from '@/js/store';

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
  window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
  console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

const app = new Vue({
  el: '#app',
  store,
  router: Routes,
  render: (h) => h(App),
});

export default app;
