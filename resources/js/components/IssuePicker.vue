<template>
  <div>
    <div>
      <h1 class="text-xl font-bold">Issues</h1>

      <div class="overflow-y-auto" style="max-height: 100px">
        <div
          :key="issue.name"
          v-for="issue in factIssues"
          @click="unsetIssue(issue)"
          class="text-green-700"
        >{{issue.name}}</div>

        <div
          :key="issue.name"
          @click="setIssue(issue)"
          v-for="issue in unrelatedIssues"
        >{{issue.name}}</div>
      </div>

      <button @click="editIssue = true" v-show="!editIssue">Add Issue</button>
      <div v-show="editIssue">
        <input placeholder="name" v-model="issueName" type="text" />
        <input placeholder="summary" v-model="issueSummary" type="text" />
        <button @click="saveIssue">Save</button>
        <button @click="editIssue = false">Close</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    fact: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      issues: [],
      factIssues: [],

      editIssue: false,
      issueName: "",
      issueSummary: ""
    };
  },

  created() {
    axios
      .get("/api/v1/issues")
      .then(({ data }) => {
        this.issues = data;
      })
      .catch(error => console.log(error));
  },

  computed: {
    unrelatedIssues() {
      return _.differenceBy(this.issues, this.factIssues, "id");
    },

    noFact() {
      return _.isEmpty(this.fact);
    }
  },

  watch: {
    fact() {
      if (this.noFact) {
        this.factIssues = [];
        return;
      }

      axios
        .get(`/api/v1/facts/${this.fact.id}/issues`)
        .then(({ data }) => (this.factIssues = data))
        .catch(error => console.log(error));
    }
  },

  methods: {
    saveIssue() {
      axios
        .post("/api/v1/issues", {
          name: this.issueName,
          summary: this.issueSummary
        })
        .then(({ data }) => {
          this.issues.push(data);
          this.issueName = "";
          this.issueSummary = "";
        });
    },

    setIssue(issue) {
      axios
        .post(`/api/v1/facts/${this.fact.id}/issues/${issue.id}`)
        .then(() => this.factIssues.push(issue))
        .catch(error => console.log(error));
    },

    unsetIssue(issue) {
      axios
        .delete(`/api/v1/facts/${this.fact.id}/issues/${issue.id}`)
        .then(() => {
          const index = this.factIssues.indexOf(issue);
          this.factIssues.splice(index, 1);
        })
        .catch(error => console.log(error));
    }
  }
};
</script>