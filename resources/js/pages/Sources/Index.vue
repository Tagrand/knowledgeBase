<template>
  <div>
    <search-vue
      class="text-blue"
      data-type="source"
      :collection="sources"
      @source-save="saveSource"
      @source-select="setSelectedSource"
    ></search-vue>
  </div>
</template>
<script>
import SearchVue from "../../components/Search.vue";

export default {
  components: { SearchVue },

  created() {
    this.$store.dispatch("getSources");
  },

  computed: {
    sources() {
      return this.$store.state.sources;
    }
  },

  methods: {
    saveSource(name) {
      axios.post("/api/v1/sources", { name }).then(({ data }) => {
        this.$store.commit("addSource", data);
        this.$router.push({ name: "source.edit" });
      });
    },

    setSelectedSource(source) {
      this.$store.commit("addSource", source);
      this.$router.push({ name: "source.edit" });
    }
  }
};
</script>