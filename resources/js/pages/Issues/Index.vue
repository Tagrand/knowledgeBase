<template>
  <div>
    <search-vue
      class="text-blue"
      data-type="issue"
      v-show="!addIssue"
      extra-info="summary"
      :collection="issues"
      @issue-save="addNewIssue"
      @issue-select="selectIssue"
    ></search-vue>

    <div v-show="addIssue">
      <input placeholder="name" v-model="issueName" type="text" />
      <input placeholder="summary" v-model="issueSummary" type="text" />
      <button @click="saveIssue">Save</button>
      <button @click="addIssue = false">Close</button>
    </div>
  </div>
</template>

<script>
import SearchVue from "../../components/Search.vue";

export default {
  components: { SearchVue },

  data() {
    return {
      addIssue: false,
      issueName: "",
      issueSummary: ""
    };
  },

  created() {
      this.$store.dispatch("getIssues");
  },

  computed: {
      issues() {
          return this.$store.state.issues;
      }
  },

  methods: {
    addNewIssue(name) {
      this.addIssue = true;
      this.issueName = name;
    },

    saveIssue() {
      axios
        .post("/api/v1/issues", {
          name: this.issueName,
          summary: this.issueSummary
        })
        .then(({ data }) => {
          this.$store.commit("addIssue", data);
          this.issueName = "";
          this.issueSummary = "";
        });
    },

    selectIssue(issue) {
      this.$router.push({ name: "issues.view", params: { id: issue.id } });
    }
  }
};
</script>
