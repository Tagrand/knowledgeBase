<template>
  <div>
    <div class="flex justify-between">
      <h1 class="text-xl font-bold">Source</h1>
      <h1 class="text-xl font-bold" v-show="isSourceSelected">Author</h1>
    </div>

    <search-vue
      class="text-blue"
      data-type="source"
      :collection="sources"
      @source-save="saveSource"
      v-show="!isSourceSelected"
      @source-select="setSelectedSource"
    ></search-vue>

    <div v-show="isSourceSelected">
      <div class="flex justify-between mb-4">
        <div>
          <p class="mr-4">{{ selectedSource.name }}</p>
          <button @click="setSelectedSource({})">Reset</button>
        </div>

        <author-picker-vue :source="selectedSource"></author-picker-vue>
      </div>

      <h1 class="text-xl font-bold">Fact</h1>

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
        <div class="flex mb-4">
          <p class="mr-4">{{ selectedFact.claim }}</p>
          <button @click="setSelectedFact({})">Reset</button>
        </div>

        <issue-picker-vue :fact="selectedFact"></issue-picker-vue>
      </div>
    </div>
  </div>
</template>

<script>
import SearchVue from "../../components/Search.vue";
import IssuePickerVue from "../../components/IssuePicker.vue";
import AuthorPickerVue from "../../components/AuthorPicker.vue";

export default {
  components: { SearchVue, IssuePickerVue, AuthorPickerVue },

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