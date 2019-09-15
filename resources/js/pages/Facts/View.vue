<template>
  <div>
    <h1 class="font-headline text-center text-5xl font-bold">
      Fact: {{ fact.claim }}
    </h1>

    <div class="text-center mb-2">
      <router-link
        :to="`/facts/${fact.id}/edit`"
        class="hover:text-blue-300"
      >
        Edit
      </router-link>
      <span>|</span>
      <router-link
        to="/facts"
        class="hover:text-blue-300"
      >
        All
      </router-link>
    </div>
    <div class="bg-grey pb-2">
      <h2 class="font-headline text-center text-2xl font-bold my-2">
        Related Issues
      </h2>
      <div class="flex justify-center px-4">
        <div
          v-for="(issue, index) in issues"
          :key="`${issue.id}${issue.name}`"
          class="text-center"
        >
          <span>{{ issue.name }}</span>
          <span v-show="index + 1 !== issues.length">|</span>
        </div>
      </div>
    </div>
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
