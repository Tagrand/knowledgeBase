<template>
  <div>
    <h1>{{ issue.name }}</h1>
    <h2>{{ issue.summary }}</h2>

    <router-link :to="`/issues/${id}/edit`">
      Edit
    </router-link>

    <h2 class="pt-4">
      Facts
    </h2>
    <div
      v-for="fact in facts"
      :key="fact.claim"
    >
      <span>{{ fact.claim }}</span>
      <span>({{ fact.source.name }})</span>
    </div>

    <h2>
      Arguments
    </h2>
    <div
      v-for="politicalArgument in politicalArguments"
      :key="politicalArgument.reason"
    >
      <span>{{ politicalArgument.reason }}</span>
      <span>({{ politicalArgument.source.name }})</span>
    </div>

    <h2 class="pt-4">
      Related issues
    </h2>
    <div
      v-for="relatedIssue in relatedIssues"
      :key="relatedIssue.name"
    >
      <router-link :to="`/issues/${relatedIssue.id}`">
        {{ relatedIssue.name }}
      </router-link>
    </div>
  </div>
</template>
<script>
import _ from 'lodash';
import axios from 'axios';

export default {
  props: {
    id: {
      required: true,
      type: String,
    },
  },

  data() {
    return {
      facts: [],
      politicalArguments: [],
    };
  },

  computed: {
    issue() {
      return this.$store.state.selectedIssue;
    },

    relatedIssues() {
      const related = [];
      this.facts.forEach((fact) => {
        fact.issues.forEach((issue) => {
          if (
            !_.some(related, (option) => option.id === issue.id)
            && issue.id !== this.id
          ) {
            related.push(issue);
          }
        });
      });
      return related;
    },
  },

  watch: {
    id() {
      this.resetIssue();
    },
  },

  created() {
    this.resetIssue();
  },

  methods: {
    resetIssue() {
      this.$store.dispatch('setSelectedIssue', this.id);

      axios
        .get(`/api/v1/issues/${this.id}/facts`)
        .then(({ data }) => { this.facts = data; });

      axios
        .get(`/api/v1/issues/${this.id}/arguments`)
        .then(({ data }) => { this.politicalArguments = data; });
    },
  },
};
</script>
