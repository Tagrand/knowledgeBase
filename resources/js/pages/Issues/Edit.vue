<template>
  <div>
    <h1>{{ issue.name }}</h1>

    <input v-model="name" type="text" />
    <input v-model="summary" type="text" />
    <button @click="save">Save</button>

    <fact-picker-vue :issue="issue"></fact-picker-vue>
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

  data() {
    return {
      name: "",
      summary: ""
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

      this.name = this.issue.name;
      this.summary = this.issue.summary;
    },

    save() {
      axios
        .patch(`/api/v1/issues/${this.issue.id}`, {
          name: this.name,
          summary: this.summary
        })
        .then(({ data }) => {
          this.$store.commit("updateSelectedIssue", data);
        });
    }
  }
};
</script>