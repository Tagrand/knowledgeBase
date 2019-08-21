<template>
  <div style="padding: 0px 100px">
    <h1>Facts</h1>

    <h2>Source</h2>

    <search-vue
      v-show="hasNoSource"
      :collection="sources"
      placeholder="source"
      @source-save="saveSource"
      @source-select="setSelectedSource"
      style="max-height: 200px; overflow-y: auto;"
    ></search-vue>

    <div v-show="!hasNoSource">
      <p>Source: {{ selectedSource.name }}</p>
      <button @click="selectedSource = {};selectedFact={}">Reset</button>

      <h2>Fact</h2>

      <search-vue
        v-show="noFact"
        placeholder="fact"
        defaultKey="claim"
        @fact-save="saveFact"
        :collection="sourceFacts"
        @fact-select="setSelectedFact"
        style="max-height: 200px; overflow-y: auto;"
      ></search-vue>

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
import SearchVue from "../../components/Search.vue";

export default {
  components: { SearchVue },

  data() {
    return {
      sources: [],
      sourceSearch: "",
      selectedSource: {},

      fact: "",
      selectedFact: {},
      sourceFacts: [],

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
    },

    selectedSource() {
      if (this.hasNoSource) {
        this.sourceFacts = [];
        return;
      }

      axios
        .get(`/api/v1/sources/${this.selectedSource.id}/facts`)
        .then(({ data }) => (this.sourceFacts = data))
        .catch(error => console.log(error));
    }
  },

  methods: {
    setSelectedSource(source) {
      this.selectedSource = source;
    },

    saveSource(source) {
      axios
        .post("/api/v1/sources", {
          name: source
        })
        .then(({ data }) => {
          this.sources.push(data);
          this.selectedSource = data;
          this.sourceSearch = "";
        });
    },

    setSelectedFact(fact) {
      this.selectedFact = fact;
    },

    saveFact(fact) {
      axios
        .post(`/api/v1/sources/${this.selectedSource.id}/facts`, {
          claim: fact
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