<template>
  <div>
    <search-vue
      class="text-blue"
      data-type="source"
      extra-info="summary"
      :collection="sources"
      @source-save="saveSource"
      @source-select="setSelectedSource"
      @source-edit="editSource"
    />

    <label for="redirect">Redirect when saving new source?</label>
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
    sources() {
      return this.$store.state.sources;
    },
  },

  created() {
    this.$store.dispatch('getSources');
  },

  methods: {
    saveSource(name) {
      axios.post('/api/v1/sources', { name }).then(({ data }) => {
        this.$store.commit('addSource', data);

        if (this.redirect) {
          this.$router.push({ name: 'source.edit', params: { id: data.id } });
        }
      });
    },

    setSelectedSource(source) {
      this.$router.push({ name: 'source.view', params: { id: source.id } });
    },

    editSource(source) {
      this.$router.push({ name: 'source.edit', params: { id: source.id } });
    },
  },
};
</script>
