<template>
  <div>
    <search-vue
      v-show="!addIssue"
      class="text-blue"
      data-type="issue"
      extra-info="summary"
      :collection="issues"
      @issue-save="addNewIssue"
      @issue-select="selectIssue"
    />

    <div v-show="addIssue">
      <input
        v-model="issueName"
        placeholder="name"
        type="text"
      >
      <input
        v-model="issueSummary"
        placeholder="summary"
        type="text"
      >
      <button @click="saveIssue">
        Save
      </button>
      <button @click="addIssue = false">
        Close
      </button>
    </div>
  </div>
</template>

<script>
import axios from 'axios';
import SearchVue from '../../components/Search.vue';

export default {
  components: { SearchVue },

  data() {
    return {
      addIssue: false,
      issueName: '',
      issueSummary: '',
    };
  },

  computed: {
    issues() {
      return this.$store.state.issues;
    },
  },

  created() {
    this.$store.dispatch('getIssues');
  },

  methods: {
    addNewIssue(name) {
      this.addIssue = true;
      this.issueName = name;
    },

    saveIssue() {
      axios
        .post('/api/v1/issues', {
          name: this.issueName,
          summary: this.issueSummary,
        })
        .then(({ data }) => {
          this.$store.commit('addIssue', data);
          this.issueName = '';
          this.issueSummary = '';
        });
    },

    selectIssue(issue) {
      this.$router.push({ name: 'issues.view', params: { id: issue.id } });
    },
  },
};
</script>
