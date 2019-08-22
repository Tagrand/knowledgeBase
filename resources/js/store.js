import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        sources: [],
        selectedSource: {},

        authors: [],

        issues: [],
    },

    mutations: {
        setSources(state, sources = []) {
            state.sources = sources;
        },

        setSelectedSource(state, id) {
            const source = _.find(state.sources, (source) => source.id === Number(id));
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
    },

    actions: {
        getSources({ commit, state }) {
            if (state.sources.length !== 0) {
                return;
            }

            return axios
                .get("/api/v1/sources")
                .then(({ data }) => {
                    commit("setSources", data);
                });
        },

        getAuthors({ commit, state }) {
            if (state.authors.length !== 0) {
                return;
            }

            axios
                .get("/api/v1/authors")
                .then(({ data }) => {
                    commit("setAuthors", data);
                });
        },

        setSelectedSource({ dispatch, commit, state }, id) {
            if (state.sources.length !== 0) {
                commit('setSelectedSource', id)
                return;
            }

            dispatch('getSources').then(() => commit('setSelectedSource', id));
        },

        getIssues({ commit, state }) {
            if (state.issues.length !== 0) {
                return;
            }

            axios
                .get("/api/v1/issues")
                .then(({ data }) => {
                    commit("setIssues", data);
                });
        }
    },
});

export default store;