<template>
  <div>
    {{ politicalArgument.reason }}
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

    <router-link :to="`/arguments`">
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
    politicalArgument() {
      return this.$store.state.selectedPoliticalArgument;
    },

    source() {
      return this.$store.state.selectedSource;
    },
  },

  created() {
    this.$store.dispatch('setSelectedPoliticalArgument', this.id)
      .then(() => {
        this.$store.dispatch('setSelectedSource', this.politicalArgument.source_id);

        console.log(this.politicalArgument);
      });

    axios
      .get(`/api/v1/arguments/${this.id}/issues`)
      .then(({ data }) => { this.issues = data; })
      .catch((error) => console.log(error));
  },
};
</script>
