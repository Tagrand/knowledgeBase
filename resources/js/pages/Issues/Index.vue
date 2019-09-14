<template>
  <div>
    <h1 class="font-headline text-center text-5xl font-bold">
      Understand the Issues
    </h1>
    <div class="md:flex justify-between">
      <div class="md:w-3/4">
        <search-vue
          class="w-full pt-4"
          data-type="issue"
          extra-info="summary"
          :collection="selectedIssues"
          @issue-save="saveIssue"
          @issue-select="selectIssue"
          @issue-edit="editIssue"
        />

        <div class="mt-4">
          <label>Redirect when saving new issue?</label>
          <input
            id="redirect"
            v-model="redirect"
            type="checkbox"
          >
        </div>
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
        <div
          v-for="source in selectedSources"
          :key="`${source.id}${source.name}`"
          @click="unSelectSource(source)"
        >
          <span class="font-bold hover:text-blue-400">
            {{ source.name }}
          </span>
          <span>|</span>
        </div>
        <div
          v-for="(source, index) in unSelectedSources"
          :key="`${source.id}${source.name}`"
          @click="selectSource(source)"
        >
          <span class="hover:text-blue-400">
            {{ source.name }}
          </span>
          <span v-show="index + 1 !== unSelectedSources.length">|</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import _ from 'lodash';
import SearchVue from '../../components/Search.vue';

export default {
  components: { SearchVue },

  data() {
    return {
      redirect: false,

      selectedSources: [],
    };
  },

  computed: {
    issues() {
      return this.$store.state.issues;
    },

    selectedIssues() {
      if (this.selectedSources.length === 0) {
        return this.issues;
      }

      const sourceId = _.map(this.selectedSources, (source) => source.id);

      return _.filter(this.issues,
        (issue) => {
          if (!issue.arguments) {
            return false;
          }

          return _.some(issue.arguments,
            (politicalArgument) => sourceId.includes(politicalArgument.source_id));
        });
    },

    sources() {
      const sources = [];
      this.issues.forEach((issue) => {
        issue.arguments.forEach((politicalArgument) => sources.push(politicalArgument.source));
      });

      return _.uniqBy(sources, 'id');
    },

    unSelectedSources() {
      return _.differenceBy(this.sources, this.selectedSources, 'id');
    },
  },

  created() {
    this.$store.dispatch('getIssues');
  },

  methods: {
    saveIssue(name) {
      axios
        .post('/api/v1/issues', {
          name,
        })
        .then(({ data }) => {
          this.$store.commit('addIssue', data);

          if (this.redirect) {
            this.$router.push({ name: 'issues.edit', params: { id: data.id } });
          }
        });
    },

    selectIssue(issue) {
      this.$router.push({ name: 'issues.view', params: { id: issue.id } });
    },

    editIssue(issue) {
      this.$router.push({ name: 'issues.edit', params: { id: issue.id } });
    },

    selectSource(source) {
      this.selectedSources.push(source);
    },

    unSelectSource(source) {
      const index = _.findIndex(this.selectedAuthors,
        (selectedSource) => selectedSource.id === source.id);
      this.selectedSources.splice(index);
    },

    clearAll() {
      this.selectedSources = [];
    },
  },
};
</script>
