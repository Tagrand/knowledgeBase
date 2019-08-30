<template>
  <div>
    <h1>{{ issue.name }}</h1>

    <input
      v-model="name"
      type="text"
    >
    <input
      v-model="summary"
      type="text"
    >
    <button @click="save">
      Save
    </button>

    <fact-picker-vue :issue="issue" />
  </div>
</template>
<script>
import axios from 'axios';
import FactPickerVue from '../../components/FactPicker.vue';

export default {
  components: { FactPickerVue },
  props: {
    id: {
      required: true,
      type: String,
    },
  },

  data() {
    return {
      name: '',
      summary: '',
    };
  },

  computed: {
    issue() {
      return this.$store.state.selectedIssue;
    },
  },

  watch: {
    id() {
      this.resetIssue();
    },
  },

  created() {
    this.resetIssue();
  },

  methods: {
    resetIssue() {
      this.$store.dispatch('setSelectedIssue', this.id).then(() => {
        this.name = this.issue.name;
        this.summary = this.issue.summary;
      });
    },

    save() {
      axios
        .patch(`/api/v1/issues/${this.issue.id}`, {
          name: this.name,
          summary: this.summary,
        })
        .then(({ data }) => {
          this.$store.commit('updateSelectedIssue', data);
        });
    },
  },
};
</script>
