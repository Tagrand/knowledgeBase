<template>
  <div>
    <h1 class="font-headline pl-12 text-4xl font-semibold">
      Understand the Issues
    </h1>
    <search-vue
      class="text-blue"
      data-type="issue"
      extra-info="summary"
      :collection="issues"
      @issue-save="saveIssue"
      @issue-select="selectIssue"
      @issue-edit="editIssue"
    />

    <label>Redirect when saving new issue?</label>
    <input
      id="redirect"
      v-model="redirect"
      type="checkbox"
    >
  </div>
</template>

<script>
import axios from 'axios';
import SearchVue from '../../components/Search.vue';

export default {
  components: { SearchVue },

  data() {
    return {
      redirect: false,
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
    saveIssue(name) {
      axios
        .post('/api/v1/issues', {
          name,
        })
        .then(({ data }) => {
          this.$store.commit('addIssue', data);

          if (this.redirect) {
            this.$router.push({ name: 'issues.edit', params: { id: data.id } });
          }
        });
    },

    selectIssue(issue) {
      this.$router.push({ name: 'issues.view', params: { id: issue.id } });
    },

    editIssue(issue) {
      this.$router.push({ name: 'issues.edit', params: { id: issue.id } });
    },
  },
};
</script>
