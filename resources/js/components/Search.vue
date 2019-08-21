<template>
  <div>
    <input type="text" :placeholder="dataType" v-model="search" />
    <button @click="saveOption" v-show="isNewOption">Save as new</button>

    <div>
      <div
        @click="selectOption(option)"
        v-bind:key="option[searchKey]"
        v-for="option in filteredCollection"
      >{{option[searchKey]}}</div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    collection: {
      type: Array,
      required: true
    },

    searchKey: {
      type: String,
      default: "name"
    },

    dataType: {
      type: String,
      required: true
    }
  },

  data() {
    return {
      search: ""
    };
  },

  computed: {
    filteredCollection() {
      if (this.search === "") {
        return this.collection;
      }

      return _.filter(this.collection, option => {
        return option[this.searchKey]
          .toLowerCase()
          .includes(this.search.toLowerCase());
      });
    },

    isNewOption() {
      if (this.search === "") {
        return false;
      }

      return !_.some(this.collection, option => {
        return (
          option[this.searchKey].toLowerCase() === this.search.toLowerCase()
        );
      });
    }
  },

  methods: {
    selectOption(option) {
      this.$emit(`${this.dataType}-select`, option);
      this.search = "";
    },

    saveOption() {
      this.$emit(`${this.dataType}-save`, this.search);
      this.search = "";
    }
  }
};
</script>