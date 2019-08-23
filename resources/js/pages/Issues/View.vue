<template>
  <div>
      <h1>{{ issue.name }} </h1>
      <h2>{{ issue.summary }} </h2>
  </div>
</template>
<script>
export default {
    props: {
        id: {
            required: true,
        }
    },

    created(){
        this.$store.dispatch("setSelectedIssue", this.id);
    },

    computed: {
        issue() {
            return this.$store.state.selectedIssue;
        }
    }
};
</script>