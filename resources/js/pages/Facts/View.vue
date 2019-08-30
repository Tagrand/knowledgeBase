<template>
  <div>
    {{ fact.claim }}
    <h2>Source</h2>
    {{ source.name }}
  </div>
</template>
<script>
export default {
  props: {
    id: {
      required: true,
      type: String,
    },
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
  },
};
</script>
