<template>
  <div>
    <h1>{{ fact.claim }}</h1>

    <input
      v-model="claim"
      type="text"
    >
    <button @click="save">
      Save
    </button>
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
      claim: '',
    };
  },

  computed: {
    fact() {
      return this.$store.state.selectedFact;
    },
  },

  watch: {
    id() {
      this.claim = this.fact.claim;
    },
  },

  created() {
    this.$store.dispatch('setSelectedFact', this.id)
      .then(() => {
        this.claim = this.fact.claim;
      });
  },

  methods: {
    save() {
      axios
        .patch(`/api/v1/facts/${this.fact.id}`, {
          claim: this.claim,
        })
        .then(({ data }) => this.$store.commit('updateSelectedFact', data));
    },
  },
};
</script>
