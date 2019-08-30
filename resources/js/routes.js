/* eslint-disable import/no-unresolved */

import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from '@/js/pages/Home';
import SourcesIndex from '@/js/pages/Sources/Index';
import SourcesView from '@/js/pages/Sources/View';
import SourcesEdit from '@/js/pages/Sources/Edit';
import IssuesIndex from '@/js/pages/Issues/Index';
import IssuesView from '@/js/pages/Issues/View';
import IssuesEdit from '@/js/pages/Issues/Edit';
import FactsIndex from '@/js/pages/Facts/Index';

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
      component: SourcesIndex,
    },
    {
      path: '/sources/:id',
      name: 'source.view',
      component: SourcesView,
      props: true,
    },
    {
      path: '/sources/:id/edit',
      name: 'source.edit',
      component: SourcesEdit,
      props: true,
    },
    {
      path: '/issues',
      name: 'issues',
      component: IssuesIndex,
    },
    {
      path: '/issues/:id',
      name: 'issues.view',
      component: IssuesView,
      props: true,
    },
    {
      path: '/issues/:id/edit',
      name: 'issues.edit',
      component: IssuesEdit,
      props: true,
    },
    {
      path: '/facts',
      name: 'facts',
      component: FactsIndex,
    },
  ],
});

export default router;
