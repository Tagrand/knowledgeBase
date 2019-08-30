<template>
  <div>
    <search-vue
      v-show="!addIssue"
      class="text-blue"
      data-type="fact"
      search-key="claim"
      :collection="facts"
      @fact-save="saveFact"
      @fact-edit="editFact"
      @fact-select="selectFact"
    />
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
      console.log(fact);
    },

    editFact(fact) {
      console.log(fact);
    },
  },
};
</script>
