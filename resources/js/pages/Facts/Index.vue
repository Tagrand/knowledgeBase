<template>
  <div>
    <h1 class="font-headline text-4xl font-semibold">
      Consult the Facts
    </h1>
    <search-vue
      class="text-blue"
      data-type="fact"
      search-key="claim"
      :collection="facts"
      @fact-save="saveFact"
      @fact-edit="editFact"
      @fact-select="selectFact"
    />

    <router-link to="/sources">
      Create new fact (create a source first)
    </router-link>
  </div>
</template>

<script>
import SearchVue from '../../components/Search.vue';

export default {
  components: { SearchVue },

  computed: {
    facts() {
      return this.$store.state.facts;
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
  },
};
</script>
