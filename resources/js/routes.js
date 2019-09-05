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
import FactsView from '@/js/pages/Facts/View';
import FactsEdit from '@/js/pages/Facts/Edit';
import ArgumentsIndex from '@/js/pages/Arguments/Index';
import ArgumentsView from '@/js/pages/Arguments/View';
import ArgumentsEdit from '@/js/pages/Arguments/Edit';

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
    {
      path: '/facts/:id',
      name: 'facts.view',
      component: FactsView,
      props: true,
    },
    {
      path: '/facts/:id/edit',
      name: 'facts.edit',
      component: FactsEdit,
      props: true,
    },
    {
      path: '/arguments',
      name: 'arguments',
      component: ArgumentsIndex,
    },
    {
      path: '/arguments/:id',
      name: 'arguments.view',
      component: ArgumentsView,
      props: true,
    },
    {
      path: '/arguments/:id/edit',
      name: 'arguments.edit',
      component: ArgumentsEdit,
      props: true,
    },
  ],
});

export default router;
