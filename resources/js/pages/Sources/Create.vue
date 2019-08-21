<template>
  <div style="padding: 0px 100px">
    <h1>Source</h1>

    <search-vue
      v-show="hasNoSource"
      :collection="sources"
      placeholder="source"
      @source-save="saveSource"
      @source-select="setSelectedSource"
      style="max-height: 200px; overflow-y: auto;"
    ></search-vue>

    <div v-show="!hasNoSource">
      <p>Source: {{ selectedSource.name }}</p>
      <button @click="setSelectedSource();setSelectedFact()">Reset</button>

      <h2>Fact</h2>

      <search-vue
        v-show="noFact"
        placeholder="fact"
        defaultKey="claim"
        @fact-save="saveFact"
        :collection="sourceFacts"
        @fact-select="setSelectedFact"
        style="max-height: 200px; overflow-y: auto;"
      ></search-vue>

      <div v-show="!noFact">
        <p>Fact: {{ selectedFact.claim }}</p>
        <button @click="setSelectedFact()">Reset</button>

        <issue-picker-vue :fact="selectedFact"></issue-picker-vue>
      </div>
    </div>
  </div>
</template>

<script>
import SearchVue from "../../components/Search.vue";
import IssuePickerVue from "../../components/IssuePicker.vue";

export default {
  components: { SearchVue, IssuePickerVue },

  data() {
    return {
      sources: [],
      selectedSource: {},

      sourceFacts: [],
      selectedFact: {}
    };
  },

  created() {
    axios
      .get("/api/v1/sources")
      .then(({ data }) => {
        this.sources = data;
      })
      .catch(error => console.log(error));
  },

  computed: {
    hasNoSource() {
      return _.isEmpty(this.selectedSource);
    },

    noFact() {
      return _.isEmpty(this.selectedFact);
    },

    unrelatedIssues() {
      return _.differenceBy(this.issues, this.factIssues, "id");
    }
  },

  watch: {
    selectedSource() {
      if (this.hasNoSource) {
        this.sourceFacts = [];
        return;
      }

      axios
        .get(`/api/v1/sources/${this.selectedSource.id}/facts`)
        .then(({ data }) => (this.sourceFacts = data))
        .catch(error => console.log(error));
    }
  },

  methods: {
    setSelectedSource(source = {}) {
      this.selectedSource = source;
    },

    saveSource(source) {
      axios
        .post("/api/v1/sources", {
          name: source
        })
        .then(({ data }) => {
          this.sources.push(data);
          this.selectedSource = data;
          this.sourceSearch = "";
        });
    },

    setSelectedFact(fact = {}) {
      this.selectedFact = fact;
    },

    saveFact(fact) {
      axios
        .post(`/api/v1/sources/${this.selectedSource.id}/facts`, {
          claim: fact
        })
        .then(({ data }) => {
          this.selectedFact = data;
        });
    }
  }
};
</script>