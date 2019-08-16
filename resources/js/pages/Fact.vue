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
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      sourceSearch: "",

      sources: [
        {
          name: "test"
        },
        {
          name: "another"
        }
      ],

      selectedSource: {}
    };
  },

  computed: {
    possibleSources() {
      if (this.sourceSearch === "") {
        return this.sources;
      }

      return _.filter(this.sources, source => {
        return source.name.includes(this.sourceSearch);
      });
    },

    isNewSource() {
      return !_.some(this.sources, source => {
        return source.name === this.sourceSearch;
      });
    },

    hasNoSource() {
      return _.isEmpty(this.selectedSource);
    }
  },

  methods: {
    saveSource() {
      this.sources.push({
        name: this.sourceSearch
      });

      this.selectedSource = this.sources.slice(-1)[0];
      this.sourceSearch = "";
    }
  }
};
</script>