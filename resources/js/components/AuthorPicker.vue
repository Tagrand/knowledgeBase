<template>
  <div>
    <div>
      <div class="overflow-y-auto flex flex-wrap" style="max-height: 100px">
        <div
          @click="unsetAuthor(issue)"
          class="text-green-700 mr-8"
          v-for="author in sourceAuthors"
          :key="author.first_name + author.last_name"
        >{{ author.first_name + ' ' + author.last_name }}</div>

        <div
          @click="setAuthor(author)"
          v-for="author in otherAuthors"
          :key="author.first_name + author.last_name"
        >{{ author.first_name + ' ' + author.last_name }}</div>
      </div>

      <button @click="addAuthor = true" v-show="!addAuthor">Add Author</button>
      <div v-show="addAuthor">
        <input placeholder="first name" v-model="firstName" type="text" />
        <input placeholder="last name" v-model="lastName" type="text" />
        <button @click="saveAuthor">Save</button>
        <button @click="addAuthor = false">Close</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    source: {
      type: Object,
      required: true
    }
  },

  data() {
    return {
      authors: [],
      sourceAuthors: [],

      addAuthor: false,

      firstName: "",
      lastName: ""
    };
  },

  created() {
    axios
      .get("/api/v1/authors")
      .then(({ data }) => {
        this.authors = data;
      })
      .catch(error => console.log(error));
  },

  computed: {
    otherAuthors() {
      return _.differenceBy(this.authors, this.sourceAuthors, "id");
    },

    noSource() {
      return _.isEmpty(this.source);
    }
  },

  watch: {
    source() {
      if (this.noSource) {
        this.sourceAuthors = [];
        return;
      }

      axios
        .get(`/api/v1/source/${this.source.id}/authors`)
        .then(({ data }) => (this.sourceAuthors = data))
        .catch(error => console.log(error));
    }
  },

  methods: {
    saveAuthor() {
      axios
        .post("/api/v1/authors", {
          first_name: this.firstName,
          last_name: this.lastName
        })
        .then(({ data }) => {
          this.authors.push(data);
          this.firstName = "";
          this.lastName = "";
        });
    },

    setAuthor(author) {
      axios
        .post(`/api/v1/sources/${this.source.id}/authors/${author.id}`)
        .then(() => this.sourceAuthors.push(author))
        .catch(error => console.log(error));
    },

    unsetAuthor(author) {
      axios
        .delete(`/api/v1/sources/${this.source.id}/authors/${author.id}`)
        .then(() => {
          const index = this.sourceAuthors.indexOf(author);
          this.sourceAuthors.splice(index, 1);
        })
        .catch(error => console.log(error));
    }
  }
};
</script>