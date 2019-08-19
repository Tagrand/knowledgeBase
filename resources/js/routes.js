import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from '@/js/pages/Home';
import Source from '@/js/pages/Source';


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
      path: '/source',
      name: 'source',
      component: Source,
    },
  ],
});

export default router;