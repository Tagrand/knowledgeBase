<template>
  <div>
    <div class="flex justify-between">
      <h1 class="text-xl font-bold">Source</h1>
      <h1 class="text-xl font-bold">Author</h1>
    </div>

    <div class="flex justify-between mb-4">
      <div>
        <div>
          <span>{{ selectedSource.name }}</span>
          <span class="mr-4" v-show="!!selectedSource.summary">({{ selectedSource.summary }})</span>
        </div>
        <router-link class="hover:text-blue-400" to="/sources">Reset</router-link>
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
</template>

<script>
import SearchVue from "../../components/Search.vue";
import IssuePickerVue from "../../components/IssuePicker.vue";
import AuthorPickerVue from "../../components/AuthorPicker.vue";

export default {
  components: { SearchVue, IssuePickerVue, AuthorPickerVue },

  props: {
    id: {
      required: true
    }
  },

  data() {
    return {
      sourceFacts: [],
      selectedFact: {}
    };
  },

  computed: {
    isFactSelected() {
      return !_.isEmpty(this.selectedFact);
    },

    selectedSource() {
      return this.$store.state.selectedSource;
    }
  },

  created() {
    this.$store.dispatch("setSelectedSource", this.id);

    axios
      .get(`/api/v1/sources/${this.id}/facts`)
      .then(({ data }) => (this.sourceFacts = data))
      .catch(error => console.log(error));
  },

  methods: {
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