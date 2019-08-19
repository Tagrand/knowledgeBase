import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from '@/js/pages/Home';
import Create from '@/js/pages/Sources/Create';


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
      path: '/sources/new',
      name: 'source.create',
      component: Create,
    },
  ],
});

export default router;