<template>
  <div>
    <h1>{{ issue.name }}</h1>
    <h2>{{ issue.summary }}</h2>

    <router-link :to="`/issues/${issue.id}/edit`">Edit</router-link>

    <h2 class="pt-4">Facts</h2>
    <div :key="fact.claim" v-for="fact in facts">
      <span>{{ fact.claim }}</span>
      <span>({{ fact.source.name }})</span>
    </div>

    <h2 class="pt-4">Related issues</h2>
    <div :key="issue.name" v-for="issue in relatedIssues">
      <router-link :to="`/issues/${issue.id}`">{{ issue.name }}</router-link>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    id: {
      required: true
    }
  },

  data() {
    return {
      facts: []
    };
  },

  created() {
    this.resetIssue();
  },

  watch: {
    id() {
      this.resetIssue();
    }
  },

  computed: {
    issue() {
      return this.$store.state.selectedIssue;
    },

    relatedIssues() {
      const related = [];
      this.facts.forEach(fact => {
        fact.issues.forEach(issue => {
          if (
            !_.some(related, option => option.id === issue.id) &&
            issue.id != this.id
          ) {
            related.push(issue);
          }
        });
      });
      return related;
    }
  },

  methods: {
    resetIssue() {
      this.$store.dispatch("setSelectedIssue", this.id);

      axios
        .get(`/api/v1/issues/${this.id}/facts`)
        .then(({ data }) => (this.facts = data));
    }
  }
};
</script>