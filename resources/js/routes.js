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
    ],
    routes: [
        {
            path: '/test',
            name: 'home',
            component: Home,
        },
      ],
});

export default router;