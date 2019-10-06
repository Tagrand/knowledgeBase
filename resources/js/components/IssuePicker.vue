<template>
  <div>
    <div>
      <h2 class="font-headline text-center text-2xl font-bold  pt-2">
        Issues
      </h2>
      <h3
        class="text-center hover:text-blue-300 mb-2 cursor-pointer"
        @click="addIssue = !addIssue"
      >
        {{ addIssue ? 'Close' : 'Create Issue' }}
      </h3>

      <div
        v-if="!addIssue"
        style="max-height: 200px"
        class="overflow-y-auto"
      >
        <div class="flex justify-between">
          <div class="w-9/20 text-center">
            <h3 class="text-1xl">
              Connected
            </h3>
            <div
              v-for="issue in parentIssues"
              :key="issue.name"
              class="font-bold hover:text-red-500 cursor-pointer"
              @click="unsetIssue(issue)"
            >
              {{ issue.name }}
            </div>
          </div>

          <div class="w-9/20 text-center">
            <h3 class="text-1xl">
              Rest
            </h3>
            <div
              v-for="issue in unrelatedIssues"
              :key="issue.name"
              class="hover:text-green-500 cursor-pointer"
              @click="setIssue(issue)"
            >
              {{ issue.name }}
            </div>
          </div>
        </div>
      </div>

      <div v-else>
        <div class="flex mb-2">
          <label
            for="name"
            class="mx-2"
          >Name:</label>
          <input
            :id="name"
            v-model="issueName"
            class="w-full mx-2 bg-grey_light pl-2"
            placeholder="name"
            type="text"
          >
        </div>
        <div class="flex mb-2">
          <label
            for="summary"
            class="mx-2"
          >Summary:</label>
          <textarea
            id="summary"
            v-model="issueSummary"
            placeholder="summary"
            class="w-full mx-2 h-1/2 bg-grey_light pl-2"
          />
        </div>

        <div class="flex justify-end">
          <button
            class="w-16 ml-8 mr-2 bg-grey_dark text-white hover:bg-grey_light"
            style="height: 40px;"
            @click="saveIssue"
          >
            Save
          </button>
        </div>
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
        .then(() => this.parentIssues.unshift(issue))
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
