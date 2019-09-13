<template>
  <div>
    <div class="flex">
      <input
        v-model="search"
        class="bg-grey w-full"
        style="width: 500px"
        type="text"
        :placeholder="dataType"
      >
      <button
        class="ml-8 bg-grey_dark text-green-100 hover:bg-grey_light"
        style="width: 100px; height: 50px"
        @click="saveOption"
      >
        Save as new
      </button>
    </div>

    <div
      class="overflow-y-auto mt-4"
      style="max-height: 800px"
    >
      <div
        v-for="option in filteredCollection"
        :key="option[searchKey]"
        class="flex pt-4"
      >
        <div style="width: 528px; overflow-x: auto">
          <span v-text="option[searchKey]" />
          <span
            v-show="!!option[extraInfo]"
            v-text="`(${option[extraInfo]})`"
          />
        </div>
        <button
          class="ml-8 bg-grey_dark text-green-100 p-1 hover:bg-grey_light"
          style="width: 128px; height: 50px"
          @click="selectOption(option)"
        >
          View
        </button>
        <button
          class="ml-8 bg-grey_dark hover:bg-grey_light"
          style="width: 128px; height: 50px"
          @click="editOption(option)"
        >
          Edit
        </button>
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

    editOption(option) {
      this.$emit(`${this.dataType}-edit`, option);
      this.search = '';
    },
  },
};
</script>
