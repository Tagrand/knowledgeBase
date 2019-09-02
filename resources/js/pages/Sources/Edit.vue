<template>
  <div>
    <div class="flex justify-between">
      <h1 class="text-xl font-bold">
        Source
      </h1>
      <h1 class="text-xl font-bold">
        Author
      </h1>
    </div>

    <div class="flex justify-between mb-4">
      <div>
        <div>
          <span>{{ selectedSource.name }}</span>
          <span
            v-show="!!selectedSource.summary"
            class="mr-4"
          >({{ selectedSource.summary }})</span>
        </div>

        <router-link
          class="hover:text-blue-400"
          :to="`/sources/${id}`"
        >
          View
        </router-link>
      </div>

      <author-picker-vue :source="selectedSource" />
    </div>

    <div class="flex justify-between">
      <div>
        <h1 class="text-xl font-bold">
          Connected arguments
        </h1>

        <search-vue
          v-show="!isArgumentSelected"
          data-type="argument"
          search-key="reason"
          :collection="sourceArguments"
          style="max-height: 200px; overflow-y: auto;"
          @argument-save="saveArgument"
          @argument-select="setSelectedArgument"
          @argument-edit="editArgument"
        />

        <div v-show="isArgumentSelected">
          <div class="flex mb-4">
            <p class="mr-4">
              {{ selectedArgument.reason }}
            </p>

            <router-link to="/arguments">
              Edit
            </router-link>

            <button @click="setSelectedArgument({})">
              Reset
            </button>
          </div>
        </div>

        <issue-picker-vue :fact="selectedFact" />
      </div>

      <div>
        <h1 class="text-xl font-bold">
          Connected facts
        </h1>

        <search-vue
          v-show="!isFactSelected"
          data-type="fact"
          search-key="claim"
          :collection="sourceFacts"
          style="max-height: 200px; overflow-y: auto;"
          @fact-save="saveFact"
          @fact-select="setSelectedFact"
          @fact-edit="editFact"
        />

        <div v-show="isFactSelected">
          <div class="flex mb-4">
            <p class="mr-4">
              {{ selectedFact.claim }}
            </p>

            <router-link :to="`/facts/${selectedFact}/edit`">
              Edit
            </router-link>

            <button @click="clearSelectedFact">
              Reset
            </button>
          </div>
        </div>

        <issue-picker-vue :fact="selectedFact" />
      </div>
    </div>
  </div>
</template>

<script>
import _ from 'lodash';
import axios from 'axios';
import SearchVue from '../../components/Search.vue';
import IssuePickerVue from '../../components/IssuePicker.vue';
import AuthorPickerVue from '../../components/AuthorPicker.vue';

export default {
  components: { SearchVue, IssuePickerVue, AuthorPickerVue },

  props: {
    id: {
      required: true,
      type: String,
    },
  },

  data() {
    return {
      sourceFacts: [],

      sourceArguments: [],
      selectedArgument: {},
    };
  },

  computed: {
    selectedFact() {
      return this.$store.state.selectedFact;
    },

    isFactSelected() {
      return !_.isEmpty(this.selectedFact);
    },

    selectedSource() {
      return this.$store.state.selectedSource;
    },

    isArgumentSelected() {
      return !_.isEmpty(this.selectedArgument);
    },
  },

  created() {
    this.$store.dispatch('setSelectedSource', this.id);
    this.$store.dispatch('getFacts');

    axios
      .get(`/api/v1/sources/${this.id}/facts`)
      .then(({ data }) => { this.sourceFacts = data; })
      .catch((error) => console.log(error));

    axios
      .get(`/api/v1/sources/${this.id}/arguments`)
      .then(({ data }) => { this.sourceArguments = data; })
      .catch((error) => console.log(error));
  },

  methods: {
    setSelectedFact(fact) {
      console.log(fact.id);
      this.$store.commit('setSelectedFact', fact.id);
    },

    saveFact(claim) {
      axios
        .post(`/api/v1/sources/${this.selectedSource.id}/facts`, { claim })
        .then(({ data }) => {
          this.sourceFacts.push(data);
          this.$store.commit('addFact', data);
          this.$store.commit('setSelectedFact', data.id);
        });
    },

    editFact(fact) {
      this.$router.push({ name: 'facts.edit', params: { id: fact.id } });
    },

    clearSelectedFact() {
      this.$store.commit('clearSelectedFact');
    },

    setSelectedArgument(argument) {
      this.selectedArgument = argument;
    },

    saveArgument(reason) {
      axios
        .post('/api/v1/arguments', {
          reason,
          source_id: this.selectedSource.id,
        })
        .then(({ data }) => {
          this.sourceArguments.push(data);
          this.$store.commit('addArgument', data);
          this.selectedArgument = data;
        });
    },

    editArgument() {
      console.log();
    },

  },
};
</script>
