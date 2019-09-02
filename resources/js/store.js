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

    facts: [],
    selectedFact: {},

    politicalArguments: [],
    selectedPoliticalArgument: {},
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

    setFacts(state, facts = []) {
      state.facts = facts;
    },

    clearSelectedFact(state) {
      state.selectedFact = {};
    },

    setSelectedFact(state, id) {
      const fact = _.find(state.facts, (storedFact) => storedFact.id === Number(id));
      state.selectedFact = fact;
    },

    updateSelectedFact(state, updatedFact) {
      const index = _.findIndex(state.facts, (fact) => fact.id === Number(updatedFact.id));
      state.facts[index] = updatedFact;
      this.state.selectedFact = updatedFact;
    },

    addFact(state, facts) {
      state.facts.push(facts);
    },

    setArguments(state, politicalArguments = []) {
      state.politicalArguments = politicalArguments;
    },

    addArgument(state, argument) {
      state.politicalArguments.push(argument);
    },

    setSelectedPoliticalArgument(state, id) {
      const politicalArgument = _.find(state.politicalArguments,
        (storedArgument) => storedArgument.id === Number(id));

      console.log(state.selectedPoliticalArgument);
      state.selectedPoliticalArgument = politicalArgument;
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
        return Promise.resolve();
      }

      return dispatch('getIssues').then(() => commit('setSelectedIssue', id));
    },

    getFacts({ commit, state }) {
      if (state.facts.length !== 0) {
        return Promise.resolve();
      }

      return axios
        .get('/api/v1/facts')
        .then(({ data }) => {
          commit('setFacts', data);
        });
    },

    setSelectedFact({ dispatch, commit, state }, id) {
      if (state.sources.length !== 0) {
        commit('setSelectedFact', id);
        return Promise.resolve();
      }

      return dispatch('getFacts').then(() => commit('setSelectedFact', id));
    },

    getArguments({ commit, state }) {
      if (state.politicalArguments.length !== 0) {
        return Promise.resolve();
      }

      return axios
        .get('/api/v1/arguments')
        .then(({ data }) => {
          commit('setArguments', data);
        });
    },

    setSelectedPoliticalArgument({ dispatch, commit, state }, id) {
      if (state.politicalArguments.length !== 0) {
        commit('setSelectedPoliticalArgument', id);
        return;
      }

      dispatch('getArguments').then(() => commit('setSelectedPoliticalArgument', id));
    },
  },
});

export default store;
