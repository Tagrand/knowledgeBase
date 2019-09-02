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
          v-for="issue in parentIssues"
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
    parent: {
      type: Object,
      required: true,
    },

    parentName: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      parentIssues: [],

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
      return _.differenceBy(this.issues, this.parentIssues, 'id');
    },

    noParent() {
      return _.isEmpty(this.parent);
    },
  },

  watch: {
    parent() {
      if (this.noParent) {
        this.parentIssues = [];
        return;
      }

      axios
        .get(`/api/v1/${this.parentName}s/${this.parent.id}/issues`)
        .then(({ data }) => { this.parentIssues = data; })
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
        .post(`/api/v1/${this.parentName}s/${this.parent.id}/issues/${issue.id}`)
        .then(() => this.parentIssues.push(issue))
        .catch((error) => console.log(error));
    },

    unsetIssue(issue) {
      axios
        .delete(`/api/v1/${this.parentName}s/${this.parent.id}/issues/${issue.id}`)
        .then(() => {
          const index = this.parentIssues.indexOf(issue);
          this.parentIssues.splice(index, 1);
        })
        .catch((error) => console.log(error));
    },
  },
};
</script>
