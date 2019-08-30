<template>
  <div>
    <h1>{{ source.name }}</h1>
    <h2>{{ source.name }}</h2>

    <router-link :to="`/sources/${source.id}/edit`">
      Edit
    </router-link>

    <h2 class="mt-4">
      Authors
    </h2>
    <p
      v-for="author in authors"
      :key="`${author.id}${author.first_name}`"
    >
      {{ author.first_name }}
      {{ author.last_name }}
    </p>

    <h2 class="mt-4">
      Facts
    </h2>
    <p
      v-for="fact in facts"
      :key="fact.claim"
    >
      {{ fact.claim }}
    </p>
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
      authors: [],
      facts: [],
    };
  },

  computed: {
    source() {
      return this.$store.state.selectedSource;
    },
  },

  created() {
    this.$store.dispatch('setSelectedSource', this.id);

    axios
      .get(`/api/v1/sources/${this.id}/authors`)
      .then(({ data }) => { this.authors = data; });

    axios
      .get(`/api/v1/sources/${this.id}/facts`)
      .then(({ data }) => { this.facts = data; });
  },
};
</script>
