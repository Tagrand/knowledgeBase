<template>
  <div>
    {{ fact.claim }}
    <h2>Source</h2>
    {{ source.name }}
    <br>
    <h2>Issues</h2>
    <p
      v-for="issue in issues"
      :key="issue.name"
    >
      {{ issue.name }}
    </p>

    <router-link :to="`/facts/${id}/edit`">
      Edit
    </router-link>
  </div>
</template>
<script>
import axios from 'axios';

export default {
  props: {
    id: {
      required: true,
      type: String,
    },
  },

  data() {
    return {
      issues: [],
    };
  },

  computed: {
    fact() {
      return this.$store.state.selectedFact;
    },

    source() {
      return this.$store.state.selectedSource;
    },
  },

  created() {
    this.$store.dispatch('setSelectedFact', this.id)
      .then(() => this.$store.dispatch('setSelectedSource', this.fact.source_id));

    axios
      .get(`/api/v1/facts/${this.id}/issues`)
      .then(({ data }) => { this.issues = data; })
      .catch((error) => console.log(error));
  },
};
</script>
