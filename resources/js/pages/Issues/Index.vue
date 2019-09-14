<template>
  <div>
    <h1 class="font-headline text-center text-5xl font-bold">
      Understand the Issues
    </h1>
    <div class="md:flex justify-between">
      <div class="md:w-3/4">
        <search-vue
          class="w-full pt-4"
          data-type="issue"
          extra-info="summary"
          :collection="issues"
          @issue-save="saveIssue"
          @issue-select="selectIssue"
          @issue-edit="editIssue"
        />

        <div class="mt-4">
          <label>Redirect when saving new issue?</label>
          <input
            id="redirect"
            v-model="redirect"
            type="checkbox"
          >
        </div>
      </div>
      <div>
        <h2 text="font-headline text-2xl pt-24">
          Filter
        </h2>
        <h3>
          Clear all
        </h3>
        <div class="font-bold">
          author.first_name author.last_name
        </div>
        <div>
          author.first_name author.last_name
        </div>
      </div>
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
