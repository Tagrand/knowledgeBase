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
      <button @click="selectedSource = {}">Reset</button>

      <h2>Fact</h2>

      <input type="text" placeholder="fact" v-model="fact" />
      <button @click="saveFact">Save</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      sourceSearch: "",

      sources: [],

      selectedSource: {},

      fact: ""
    };
  },

  created() {
    axios
      .get("/api/v1/sources")
      .then(({ data }) => {
        this.sources = data;
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
        .then(() => (this.fact = ""));
    }
  }
};
</script>