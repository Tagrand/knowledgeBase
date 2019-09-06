<template>
  <div>
    {{ politicalArgument.reason }}
    {{ politicalArgument.summary }}

    <br>
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

    <h2>Facts For</h2>
    <p
      v-for="fact in factsFor"
      :key="`${fact.id}{fact.claim}`"
      v-text="fact.claim"
    />
    <h2>Facts Against</h2>
    <p
      v-for="fact in factsAgainst"
      :key="`${fact.id}{fact.claim}`"
      v-text="fact.claim"
    />

    <router-link :to="`/arguments/${id}/edit`">
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
      facts: [],
    };
  },

  computed: {
    politicalArgument() {
      return this.$store.state.selectedPoliticalArgument;
    },

    source() {
      return this.$store.state.selectedSource;
    },

    factsFor() {
      return this.facts.filter((fact) => fact.pivot.is_supportive);
    },

    factsAgainst() {
      return this.facts.filter((fact) => !fact.pivot.is_supportive);
    },
  },

  created() {
    this.$store.dispatch('setSelectedPoliticalArgument', this.id)
      .then(() => {
        this.$store.dispatch('setSelectedSource', this.politicalArgument.source_id);
      });

    axios
      .get(`/api/v1/arguments/${this.id}/issues`)
      .then(({ data }) => { this.issues = data; })
      .catch((error) => console.log(error));

    axios
      .get(`/api/v1/arguments/${this.id}/facts`)
      .then(({ data }) => { this.facts = data; })
      .catch((error) => console.log(error));
  },
};
</script>
