<template>
  <div>
    <h1>{{ issue.name }}</h1>
    <h2>{{ issue.summary }}</h2>

    <h2 class="pt-4">Facts</h2>
    <div :key="fact.claim" v-for="fact in facts">
      <span>{{ fact.claim }}</span>
      <span>({{ fact.source.name }})</span>
    </div>

  </div>
</template>
<script>

export default {
  components: { FactPickerVue },
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