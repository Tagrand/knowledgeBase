<template>
  <div>
    <div>
      <h1 class="text-xl font-bold">Facts</h1>

      <div class="overflow-y-auto flex flex-wrap" style="max-height: 100px">
        <div
          :key="fact.claim"
          v-for="fact in issueFacts"
          @click="unsetFact(fact)"
          class="text-green-700 mr-8"
        >{{fact.name}}</div>

        <div
          class="mr-8"
          :key="fact.claim"
          @click="setFact(fact)"
          v-for="fact in unrelatedFacts"
        >{{fact.claim}}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    issue: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      issueFacts: [],
      facts: []
    };
  },

  created() {
    axios
      .get("/api/v1/facts")
      .then(({ data }) => (this.facts = data))
      .catch(error => console.log(error));
  },

  computed: {
    unrelatedFacts() {
      return _.differenceBy(this.facts, this.issueFacts, "id");
    },

    noFact() {
      return _.isEmpty(this.issue);
    }
  },

  watch: {
    fact() {
      if (this.noFact) {
        this.factIssues = [];
        return;
      }

      axios
        .get(`/api/v1/issues/${this.issue.id}/facts`)
        .then(({ data }) => (this.issueFacts = data))
        .catch(error => console.log(error));
    }
  },

  methods: {
    setFact(fact) {
      axios
        .post(`/api/v1/facts/${fact.id}/issues/${this.issue.id}`)
        .then(() => this.issueFacts.push(fact))
        .catch(error => console.log(error));
    },

    unsetFact(fact) {
      axios
        .delete(`/api/v1/facts/${fact.id}/issues/${this.issue.id}`)
        .then(() => {
          const index = this.issueFacts.indexOf(fact);
          this.issueFacts.splice(index, 1);
        })
        .catch(error => console.log(error));
    }
  }
};
</script>