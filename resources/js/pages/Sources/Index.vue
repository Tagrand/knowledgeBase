<template>
  <div>
    <search-vue
      class="text-blue"
      data-type="source"
      extra-info="summary"
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
    saveSource(info) {
      let name, summary;
      console.log(info);
      [name, summary] = info.split('\*\*');

      axios.post("/api/v1/sources", { name, summary }).then(({ data }) => {
        this.$store.commit("addSource", data);
        this.$router.push({ name: "source.edit", params: { id: data.id }});
      });
    },

    setSelectedSource(source) {
      this.$router.push({ name: "source.edit", params: { id: source.id } });
    }
  }
};
</script>