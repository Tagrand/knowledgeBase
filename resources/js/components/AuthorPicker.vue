<template>
  <div>
    <h2 class="font-headline text-center text-2xl font-bold pt-2">
      Authors
    </h2>
    <h3
      class="text-center hover:text-blue-300 mb-2"
      @click="addAuthor = !addAuthor"
    >
      {{ addAuthor ? 'Close' : 'Create Author' }}
    </h3>

    <div
      v-if="!addAuthor"
      style="max-height: 200px"
      class="overflow-y-auto"
    >
      <div class="flex justify-between">
        <div class="w-9/20 text-center">
          <h3 class="text-1xl">
            Connected
          </h3>
          <div
            v-for="author in sourceAuthors"
            :key="author.first_name + author.last_name"
            class="font-bold hover:text-red-500 cursor-pointer"
            @click="unsetAuthor(author)"
          >
            {{ author.first_name + ' ' + author.last_name }}
          </div>
        </div>

        <div class="w-9/20 text-center">
          <h3 class="text-1xl">
            Rest
          </h3>
          <div
            v-for="author in otherAuthors"
            :key="author.first_name + author.last_name"
            class="hover:text-green-500 cursor-pointer"
            @click="setAuthor(author)"
          >
            {{ author.first_name + ' ' + author.last_name }}
          </div>
        </div>
      </div>
    </div>

    <div v-else>
      <div>
        <div class="flex mb-2">
          <label
            for="first-name"
            class="mx-2"
          >First name:</label>
          <input
            id="first-name"
            v-model="firstName"
            placeholder="first name"
            class="w-full mx-2 bg-grey_light pl-2"
            type="text"
          >
        </div>
        <div class="flex mb-2">
          <label
            for="last-name"
            class="mx-2"
          >Last name:</label>
          <input
            id="last-name"
            v-model="lastName"
            class="w-full mx-2 bg-grey_light pl-2"
            placeholder="last name"
            type="text"
          >
        </div>

        <div class="flex justify-end">
          <button
            class="w-16 ml-8 mr-2 bg-grey_dark text-white hover:bg-grey_light"
            style="height: 40px;"
            @click="saveAuthor"
          >
            Save
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import _ from 'lodash';
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
      return _.orderBy(this.$store.state.authors, ['last_name', 'first_name']);
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
        .then(({ data }) => { this.sourceAuthors = data; })
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
          this.$store.commit('addAuthor', data);
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
