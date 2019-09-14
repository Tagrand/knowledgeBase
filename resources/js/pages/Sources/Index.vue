<template>
  <div>
    <h1 class="font-headline pl-12 text-center text-5xl font-bold">
      Find your Source
    </h1>
    <div class="md:flex justify-between">
      <div class="md:w-3/4">
        <search-vue
          class="w-full pt-4"
          data-type="source"
          extra-info="summary"
          :collection="selectedSources"
          @source-save="saveSource"
          @source-select="setSelectedSource"
          @source-edit="editSource"
        />

        <div class="mt-4">
          <label>Redirect when saving new source?</label>
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
        <h3 @click="clearFilters">
          Clear all
        </h3>
        <div
          v-for="author in selectedAuthors"
          :key="`${author.id}${author.first_name}`"
          class="font-bold"
          @click="unSelectAuthor(author)"
        >
          {{ author.first_name }} {{ author.last_name }}
        </div>
        <div
          v-for="author in unselectedAuthors"
          :key="`${author.id}${author.first_name}`"
          @click="selectAuthor(author)"
        >
          {{ author.first_name }} {{ author.last_name }}
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

      selectedAuthors: [],
    };
  },

  computed: {
    sources() {
      return this.$store.state.sources;
    },

    selectedSources() {
      if (_.isEmpty(this.selectedAuthors)) {
        return this.sources;
      }

      const authorId = _.map(this.selectedAuthors, (author) => author.id);

      return _.filter(this.sources,
        (source) => _.some(source.authors, (author) => authorId.includes(author.id)));
    },

    authors() {
      const authors = [];
      this.sources.forEach((source) => {
        source.authors.forEach((author) => authors.push(author));
      });

      return _.uniqBy(authors, 'id');
    },

    unselectedAuthors() {
      return _.differenceBy(this.authors, this.selectedAuthors, 'id');
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

    selectAuthor(author) {
      this.selectedAuthors.push(author);
    },

    unSelectAuthor(author) {
      const index = _.findIndex(this.selectedAuthors,
        (selectedAuthor) => selectedAuthor.id === author.id);
      this.selectedAuthors.splice(index, 1);
    },

    clearFilters() {
      this.selectedAuthors = [];
    },
  },
};
</script>
