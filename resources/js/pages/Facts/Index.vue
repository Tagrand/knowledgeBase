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
import axios from 'axios';
import SearchVue from '../../components/Search.vue';

export default {
  components: { SearchVue },

  data() {
    return {
      facts: [],
    };
  },

  created() {
    axios
      .get('/api/v1/facts')
      .then(({ data }) => { this.facts = data; });
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
