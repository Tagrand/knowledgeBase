<template>
  <div>
    <div>
      <h1 class="text-xl font-bold">
        Issues
      </h1>

      <div
        class="overflow-y-auto flex flex-wrap"
        style="max-height: 100px"
      >
        <div
          v-for="issue in factIssues"
          :key="issue.name"
          class="text-green-700 mr-8"
          @click="unsetIssue(issue)"
        >
          {{ issue.name }}
        </div>

        <div
          v-for="issue in unrelatedIssues"
          :key="issue.name"
          class="mr-8"
          @click="setIssue(issue)"
        >
          {{ issue.name }}
        </div>
      </div>

      <button
        v-show="!addIssue"
        @click="addIssue = true"
      >
        Add Issue
      </button>
      <div v-show="addIssue">
        <input
          v-model="issueName"
          placeholder="name"
          type="text"
        >
        <input
          v-model="issueSummary"
          placeholder="summary"
          type="text"
        >
        <button @click="saveIssue">
          Save
        </button>
        <button @click="addIssue = false">
          Close
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import _ from 'lodash';
import axios from 'axios';

export default {
  props: {
    fact: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      factIssues: [],

      addIssue: false,
      issueName: '',
      issueSummary: '',
    };
  },

  computed: {
    issues() {
      return this.$store.state.issues;
    },

    unrelatedIssues() {
      return _.differenceBy(this.issues, this.factIssues, 'id');
    },

    noFact() {
      return _.isEmpty(this.fact);
    },
  },

  watch: {
    fact() {
      if (this.noFact) {
        this.factIssues = [];
        return;
      }

      axios
        .get(`/api/v1/facts/${this.fact.id}/issues`)
        .then(({ data }) => { this.factIssues = data; })
        .catch((error) => console.log(error));
    },
  },

  created() {
    this.$store.dispatch('getIssues');
  },

  methods: {
    saveIssue() {
      axios
        .post('/api/v1/issues', {
          name: this.issueName,
          summary: this.issueSummary,
        })
        .then(({ data }) => {
          this.$store.commit('addIssue', data);
          this.issueName = '';
          this.issueSummary = '';
        });
    },

    setIssue(issue) {
      axios
        .post(`/api/v1/facts/${this.fact.id}/issues/${issue.id}`)
        .then(() => this.factIssues.push(issue))
        .catch((error) => console.log(error));
    },

    unsetIssue(issue) {
      axios
        .delete(`/api/v1/facts/${this.fact.id}/issues/${issue.id}`)
        .then(() => {
          const index = this.factIssues.indexOf(issue);
          this.factIssues.splice(index, 1);
        })
        .catch((error) => console.log(error));
    },
  },
};
</script>
