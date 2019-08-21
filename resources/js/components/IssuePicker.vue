<template>
  <div>
    <div>
      <h2>Issue</h2>

      <div v-bind:key="issue.name" v-for="issue in factIssues">
        <strong>{{issue.name}}</strong>
      </div>

      <div
        @click="setIssue(issue)"
        v-bind:key="issue.name"
        v-for="issue in unrelatedIssues"
        style="max-height: 200px; overflow-y: auto;"
      >{{issue.name}}</div>

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
    }
  }
};
</script>