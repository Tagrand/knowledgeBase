<template>
  <div>
    <search-vue
      class="text-blue"
      data-type="source"
      extra-info="summary"
      :collection="sources"
      @source-save="saveSource"
      @source-select="setSelectedSource"
    />
  </div>
</template>
<script>
import axios from 'axios';
import SearchVue from '../../components/Search.vue';

export default {
  components: { SearchVue },

  computed: {
    sources() {
      return this.$store.state.sources;
    },
  },

  created() {
    this.$store.dispatch('getSources');
  },

  methods: {
    saveSource(info) {
      let name; let summary;

      // eslint-disable-next-line prefer-const
      [name, summary] = info.split('**');

      axios.post('/api/v1/sources', { name, summary }).then(({ data }) => {
        this.$store.commit('addSource', data);
        this.$router.push({ name: 'source.edit', params: { id: data.id } });
      });
    },

    setSelectedSource(source) {
      this.$router.push({ name: 'source.edit', params: { id: source.id } });
    },
  },
};
</script>
