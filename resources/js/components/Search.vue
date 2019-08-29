<template>
  <div>
    <input
      v-model="search"
      type="text"
      :placeholder="dataType"
    >
    <button
      v-show="isNewOption"
      @click="saveOption"
    >
      Save as new
    </button>

    <div
      class="overflow-y-auto"
      style="max-height: 100px"
    >
      <div
        v-for="option in filteredCollection"
        :key="option[searchKey]"
        @click="selectOption(option)"
      >
        <span v-text="option[searchKey]" />
        <span
          v-show="!!option[extraInfo]"
          v-text="`(${option[extraInfo]})`"
        />
      </div>
    </div>
  </div>
</template>

<script>
import _ from 'lodash';

export default {
  props: {
    collection: {
      type: Array,
      required: true,
    },

    searchKey: {
      type: String,
      default: 'name',
    },

    dataType: {
      type: String,
      required: true,
    },

    extraInfo: {
      type: String,
      default: null,
    },
  },

  data() {
    return {
      search: '',
    };
  },

  computed: {
    filteredCollection() {
      if (this.search === '') {
        return this.collection;
      }

      return _.filter(this.collection, (option) => option[this.searchKey]
        .toLowerCase()
        .includes(this.search.toLowerCase()));
    },

    isNewOption() {
      if (this.search === '') {
        return false;
      }

      return !_.some(this.collection, (option) => (
        option[this.searchKey].toLowerCase() === this.search.toLowerCase()
      ));
    },
  },

  methods: {
    selectOption(option) {
      this.$emit(`${this.dataType}-select`, option);
      this.search = '';
    },

    saveOption() {
      this.$emit(`${this.dataType}-save`, this.search);
      this.search = '';
    },
  },
};
</script>
