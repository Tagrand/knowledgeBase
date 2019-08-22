import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from '@/js/pages/Home';
import Edit from '@/js/pages/Sources/Edit';
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
      path: '/sources/:id/edit',
      name: 'source.edit',
      component: Edit,
      props: true,
    },
  ],
});

export default router;