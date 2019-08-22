import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        sources: [],
        selectedSource: {},
    },

    mutations: {
        setSources(state, sources = []) {
            state.sources = sources;
        },

        addSource(state, source) {
            state.sources.push(source);
            state.selectedSource = source;
        },
    },

    actions: {
        getSources({ commit, state }) {
            if (state.sources.length !== 0) {
                return;
            }

            axios
                .get("/api/v1/sources")
                .then(({ data }) => {
                    commit("setSources", data);
                });
        }
    }


});

export default store;