<template>
  <div>
    <h1>{{ issue.name }}</h1>
    <h2>{{ issue.summary }}</h2>

    <fact-picker-vue :issue=issue></fact-picker-vue>
  </div>
</template>
<script>
import FactPickerVue from "../../components/FactPicker.vue";

export default {
  components: { FactPickerVue },
  props: {
    id: {
      required: true
    }
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
    }
  }
};
</script>