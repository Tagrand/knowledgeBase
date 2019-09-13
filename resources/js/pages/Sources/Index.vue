<template>
  <div>
    <div class="flex">
      <div style="width: 50%">
        <h1 class="font-headline pl-12 text-4xl font-semibold">
          Find your Source
        </h1>
        <search-vue
          class="w-full pt-4"
          data-type="source"
          extra-info="summary"
          :collection="sources"
          @source-save="saveSource"
          @source-select="setSelectedSource"
          @source-edit="editSource"
        />

        <label>Redirect when saving new source?</label>
        <input
          id="redirect"
          v-model="redirect"
          type="checkbox"
        >
      </div>
      <div>
        <h2 text="font-headline text-2xl pt-24">
          Filter
        </h2>
        <div
          v-for="author in authors"
          :key="`${author.id}${author.first_name}`"
        >
          {{ author.first_name }} {{ author.last_name }}
        </div>
      </div>
    </div>
  </div>
  </div>
</template>
<script>
import axios from 'axios';
import _ from 'lodash';
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

    authors() {
      const authors = [];
      this.sources.forEach((source) => {
        source.authors.forEach((author) => authors.push(author));
      });

      return _.uniqBy(authors, 'id');
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
