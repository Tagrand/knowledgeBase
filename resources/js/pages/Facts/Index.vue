<template>
  <div>
    <h1 class="font-headline text-center text-5xl font-bold">
      Consult the Facts
    </h1>
    <div class="md:flex justify-between">
      <div class="md:w-3/4">
        <search-vue
          class="text-blue"
          data-type="fact"
          search-key="claim"
          :collection="selectedFacts"
          @fact-save="saveFact"
          @fact-edit="editFact"
          @fact-select="selectFact"
        />
        <router-link to="/sources">
          Create new fact (create a source first)
        </router-link>
      </div>
      <div class="md:w-1/5">
        <h2 class="font-headline text-center text-3xl font-bold">
          Filters
        </h2>
        <h3
          class="text-center hover:text-blue-400 mb-4"
          @click="clearAll"
        >
          Clear all
        </h3>
        <h2 class="font-headline text-center text-2xl">
          Sources
        </h2>
        <filter-vue
          class="flex"
          :collection="sources"
          :selected="selectedSources"
          type="source"
        />
      </div>
    </div>
  </div>
</template>

<script>
import _ from 'lodash';
import SearchVue from '../../components/Search.vue';
import FilterVue from '../../components/Filter.vue';

export default {
  components: { SearchVue, FilterVue },

  data() {
    return {
      selectedSources: [],
    };
  },

  computed: {
    facts() {
      return this.$store.state.facts;
    },

    selectedFacts() {
      if (this.selectedSources.length === 0) {
        return this.facts;
      }

      const sourceId = _.map(this.selectedSources, (source) => source.id);

      return _.filter(this.facts, (fact) => sourceId.includes(fact.source_id));
    },


    sources() {
      return _.uniqBy(this.facts.map((fact) => fact.source), 'id');
    },
  },

  created() {
    this.$store.dispatch('getFacts');
  },

  methods: {
    saveFact(claim) {
      console.log(claim);
    },

    selectFact(fact) {
      this.$router.push({ name: 'facts.view', params: { id: fact.id } });
    },

    editFact(fact) {
      this.$router.push({ name: 'facts.edit', params: { id: fact.id } });
    },

    clearAll() {
      this.selectedSources = [];
    },
  },
};
</script>
