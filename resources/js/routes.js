import Vue from 'vue';
import VueRouter from 'vue-router';

import Home from '@/js/components/Home';

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'home',
      component: Home,
    },
    {
      path: '/test',
      name: 'test',
      component: Home,
    },
  ],
});

export default router;