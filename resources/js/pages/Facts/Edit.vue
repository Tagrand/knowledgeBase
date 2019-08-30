<template>
  <div>
    <h1>{{ fact.claim }}</h1>
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
  },

  created() {
    this.$store.dispatch('setSelectedFact', this.id);
  },
};
</script>
