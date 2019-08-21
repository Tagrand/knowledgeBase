<template>
  <div style="padding: 0px 100px">
    <h1 class="text-blue-700">Source</h1>

    <search-vue
    class="text-blue"
      data-type="source"
      :collection="sources"
      @source-save="saveSource"
      v-show="!isSourceSelected"
      @source-select="setSelectedSource"
      style="max-height: 200px; overflow-y: auto;"
    ></search-vue>

    <div v-show="isSourceSelected">
      <p>Source: {{ selectedSource.name }}</p>
      <button @click="setSelectedSource({})">Reset</button>

      <h2>Fact</h2>

      <search-vue
        data-type="fact"
        searchKey="claim"
        @fact-save="saveFact"
        v-show="!isFactSelected"
        :collection="sourceFacts"
        @fact-select="setSelectedFact"
        style="max-height: 200px; overflow-y: auto;"
      ></search-vue>

      <div v-show="isFactSelected">
        <p>Fact: {{ selectedFact.claim }}</p>
        <button @click="setSelectedFact({})">Reset</button>

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
    isSourceSelected() {
      return !_.isEmpty(this.selectedSource);
    },

    isFactSelected() {
      return !_.isEmpty(this.selectedFact);
    }
  },

  watch: {
    selectedSource() {
      if (!this.isSourceSelected) {
        this.setSelectedFact({});
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
    setSelectedSource(source) {
      this.selectedSource = source;
    },

    saveSource(name) {
      axios.post("/api/v1/sources", { name }).then(({ data }) => {
        this.sources.push(data);
        this.selectedSource = data;
      });
    },

    setSelectedFact(fact) {
      this.selectedFact = fact;
    },

    saveFact(claim) {
      axios
        .post(`/api/v1/sources/${this.selectedSource.id}/facts`, { claim })
        .then(({ data }) => {
          this.sourceFacts.push(data);
          this.selectedFact = data;
        });
    }
  }
};
</script>