<template>
  <div>
    <div>
      <h1 class="text-xl font-bold">
        Facts
      </h1>

      <div
        class="overflow-y-auto flex flex-wrap"
        style="max-height: 100px"
      >
        <div
          v-for="fact in issueFacts"
          :key="fact.claim"
          class="text-green-700 mr-8"
          @click="unsetFact(fact)"
        >
          {{ fact.name }}
        </div>

        <div
          v-for="fact in unrelatedFacts"
          :key="fact.claim"
          class="mr-8"
          @click="setFact(fact)"
        >
          {{ fact.claim }}
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
    issue: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      issueFacts: [],
      facts: [],
    };
  },

  computed: {
    unrelatedFacts() {
      return _.differenceBy(this.facts, this.issueFacts, 'id');
    },

    noFact() {
      return _.isEmpty(this.issue);
    },
  },

  watch: {
    fact() {
      if (this.noFact) {
        this.factIssues = [];
        return;
      }

      axios
        .get(`/api/v1/issues/${this.issue.id}/facts`)
        .then(({ data }) => { this.issueFacts = data; })
        .catch((error) => console.log(error));
    },
  },

  created() {
    axios
      .get('/api/v1/facts')
      .then(({ data }) => { this.facts = data; })
      .catch((error) => console.log(error));
  },

  methods: {
    setFact(fact) {
      axios
        .post(`/api/v1/facts/${fact.id}/issues/${this.issue.id}`)
        .then(() => this.issueFacts.push(fact))
        .catch((error) => console.log(error));
    },

    unsetFact(fact) {
      axios
        .delete(`/api/v1/facts/${fact.id}/issues/${this.issue.id}`)
        .then(() => {
          const index = this.issueFacts.indexOf(fact);
          this.issueFacts.splice(index, 1);
        })
        .catch((error) => console.log(error));
    },
  },
};
</script>
