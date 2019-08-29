<template>
  <div>
    <div>
      <div
        class="overflow-y-auto flex flex-wrap"
        style="max-height: 100px"
      >
        <div
          v-for="author in sourceAuthors"
          :key="author.first_name + author.last_name"
          class="text-green-700 mr-8"
          @click="unsetAuthor(issue)"
        >
          {{ author.first_name + ' ' + author.last_name }}
        </div>

        <div
          v-for="author in otherAuthors"
          :key="author.first_name + author.last_name"
          @click="setAuthor(author)"
        >
          {{ author.first_name + ' ' + author.last_name }}
        </div>
      </div>

      <button
        v-show="!addAuthor"
        @click="addAuthor = true"
      >
        Add Author
      </button>
      <div v-show="addAuthor">
        <input
          v-model="firstName"
          placeholder="first name"
          type="text"
        >
        <input
          v-model="lastName"
          placeholder="last name"
          type="text"
        >
        <button @click="saveAuthor">
          Save
        </button>
        <button @click="addAuthor = false">
          Close
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    source: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      sourceAuthors: [],

      addAuthor: false,

      firstName: '',
      lastName: '',
    };
  },

  computed: {
    authors() {
      return this.$store.state.authors;
    },

    otherAuthors() {
      return _.differenceBy(this.authors, this.sourceAuthors, 'id');
    },

    noSource() {
      return _.isEmpty(this.source);
    },
  },

  watch: {
    source() {
      if (this.noSource) {
        this.sourceAuthors = [];
        return;
      }

      axios
        .get(`/api/v1/sources/${this.source.id}/authors`)
        .then(({ data }) => (this.sourceAuthors = data))
        .catch((error) => console.log(error));
    },
  },

  created() {
    this.$store.dispatch('getAuthors');
  },

  methods: {
    saveAuthor() {
      axios
        .post('/api/v1/authors', {
          first_name: this.firstName,
          last_name: this.lastName,
        })
        .then(({ data }) => {
          this.authors.push(data);
          this.firstName = '';
          this.lastName = '';
        });
    },

    setAuthor(author) {
      axios
        .post(`/api/v1/sources/${this.source.id}/authors/${author.id}`)
        .then(() => this.sourceAuthors.push(author))
        .catch((error) => console.log(error));
    },

    unsetAuthor(author) {
      axios
        .delete(`/api/v1/sources/${this.source.id}/authors/${author.id}`)
        .then(() => {
          const index = this.sourceAuthors.indexOf(author);
          this.sourceAuthors.splice(index, 1);
        })
        .catch((error) => console.log(error));
    },
  },
};
</script>
