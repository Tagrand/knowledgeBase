import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from '@/js/pages/Home';
import Create from '@/js/pages/Sources/Create';
import Index from '@/js/pages/Sources/Index';


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
      path: '/sources',
      name: 'sources',
      component: Index,
    },
    {
      path: '/sources/edit',
      name: 'source.edit',
      component: Create,
    },
  ],
});

export default router;