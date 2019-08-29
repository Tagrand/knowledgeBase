/* eslint-disable no-param-reassign */
import Vue from 'vue';
import _ from 'lodash';
import Vuex from 'vuex';
import axios from 'axios';

Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    sources: [],
    selectedSource: {},

    authors: [],

    issues: [],
    selectedIssue: {},
  },

  mutations: {
    setSources(state, sources = []) {
      state.sources = sources;
    },

    setSelectedSource(state, id) {
      const source = _.find(state.sources, (storedSource) => storedSource.id === Number(id));
      state.selectedSource = source;
    },

    addSource(state, source) {
      state.sources.push(source);
      state.selectedSource = source;
    },

    setAuthors(state, authors = []) {
      state.authors = authors;
    },

    setIssues(state, issues = []) {
      state.issues = issues;
    },

    addIssue(state, issue) {
      state.issues.push(issue);
    },

    setSelectedIssue(state, id) {
      const issue = _.find(state.issues, (storedIssue) => storedIssue.id === Number(id));
      state.selectedIssue = issue;
    },

    updateSelectedIssue(state, updatedIssue) {
      const index = _.findIndex(state.issues, (issue) => issue.id === Number(updatedIssue.id));
      state.issues[index] = updatedIssue;
      this.state.selectedIssue = updatedIssue;
    },
  },

  actions: {
    getSources({ commit, state }) {
      if (state.sources.length !== 0) {
        return Promise.resolve();
      }

      return axios
        .get('/api/v1/sources')
        .then(({ data }) => {
          commit('setSources', data);
        });
    },

    getAuthors({ commit, state }) {
      if (state.authors.length !== 0) {
        return;
      }

      axios
        .get('/api/v1/authors')
        .then(({ data }) => {
          commit('setAuthors', data);
        });
    },

    setSelectedSource({ dispatch, commit, state }, id) {
      if (state.sources.length !== 0) {
        commit('setSelectedSource', id);
        return;
      }

      dispatch('getSources').then(() => commit('setSelectedSource', id));
    },

    getIssues({ commit, state }) {
      if (state.issues.length !== 0) {
        return Promise.resolve();
      }

      return axios
        .get('/api/v1/issues')
        .then(({ data }) => {
          commit('setIssues', data);
        });
    },

    setSelectedIssue({ dispatch, commit, state }, id) {
      if (state.issues.length !== 0) {
        commit('setSelectedIssue', id);
        return;
      }

      dispatch('getIssues').then(() => commit('setSelectedIssue', id));
    },
  },
});

export default store;
