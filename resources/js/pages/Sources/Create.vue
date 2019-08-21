<template>
  <div style="padding: 0px 100px">
    <h1>Facts</h1>

    <h2>Source</h2>

    <div v-show="hasNoSource">
      <input type="text" placeholder="source" v-model="sourceSearch" />
      <button @click="saveSource" v-show="isNewSource">Save as new</button>

      <div style="max-height: 200px; overflow-y: auto;">
        <div
          v-bind:key="source.name"
          v-for="source in possibleSources"
          @click="selectedSource = source"
        >{{source.name}}</div>
      </div>
    </div>

    <div v-show="!hasNoSource">
      <p>Source: {{ selectedSource.name }}</p>
      <button @click="selectedSource = {};selectedFact={}">Reset</button>

      <h2>Fact</h2>

      <div v-show="noFact">
        <input type="text" placeholder="fact" v-model="fact" />
        <button @click="saveFact">Save</button>
      </div>
      <div v-show="!noFact">
        <p>Fact: {{ selectedFact.claim }}</p>
        <button @click="selectedFact={}">Reset</button>

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
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      sources: [],
      sourceSearch: "",
      selectedSource: {},

      fact: "",
      selectedFact: {},

      issues: [],
      factIssues: [],

      editIssue: false,
      issueName: "",
      issueSummary: ""
    };
  },

  created() {
    axios
      .get("/api/v1/sources")
      .then(({ data }) => {
        this.sources = data;
      })
      .catch(error => console.log(error));

    axios
      .get("/api/v1/issues")
      .then(({ data }) => {
        this.issues = data;
      })
      .catch(error => console.log(error));
  },

  computed: {
    possibleSources() {
      if (this.sourceSearch === "") {
        return this.sources;
      }

      return _.filter(this.sources, source => {
        return source.name
          .toLowerCase()
          .includes(this.sourceSearch.toLowerCase());
      });
    },

    isNewSource() {
      return !_.some(this.sources, source => {
        return source.name.toLowerCase() === this.sourceSearch.toLowerCase();
      });
    },

    hasNoSource() {
      return _.isEmpty(this.selectedSource);
    },

    noFact() {
      return _.isEmpty(this.selectedFact);
    },

    unrelatedIssues() {
      return _.differenceBy(this.issues, this.factIssues, "id");
    }
  },

  watch: {
    selectedFact() {
      if (this.noFact) {
        this.factIssues = [];
        return;
      }

      factIssues: [],
        axios
          .get(`/api/v1/facts/${this.selectedFact.id}/issues`)
          .then(({ data }) => (this.factIssues = data))
          .catch(error => console.log(error));
    }
  },

  methods: {
    saveSource() {
      axios
        .post("/api/v1/sources", {
          name: this.sourceSearch
        })
        .then(({ data }) => {
          this.sources.push(data);
          this.selectedSource = data;
          this.sourceSearch = "";
        });
    },

    saveFact() {
      axios
        .post(`/api/v1/sources/${this.selectedSource.id}/facts`, {
          claim: this.fact
        })
        .then(({ data }) => {
          this.selectedFact = data;
        });
    },

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
        .post(`/api/v1/facts/${this.selectedFact.id}/issues/${issue.id}`)
        .then(() => this.factIssues.push(issue))
        .catch(error => console.log(error));
    }
  }
};
</script>