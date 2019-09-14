<template>
  <div>
    <div class="flex justify-between">
      <input
        v-model="search"
        class="bg-grey pl-2 w-4/5"
        type="text"
        :placeholder="dataType"
      >
      <button
        class="w-32 ml-8 bg-grey_dark text-white hover:bg-grey_light"
        style=" height: 50px"
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
        class="flex justify-between pt-4 content-center"
      >
        <div
          style="overflow-x: auto"
          class="w-1/2"
        >
          <span v-text="option[searchKey]" />
          <span
            v-show="!!option[extraInfo]"
            v-text="`(${option[extraInfo]})`"
          />
        </div>
        <div>
          <button
            class="md:ml-4 md:mr-8  md:w-32 w-12 bg-grey_dark text-white p-1 hover:bg-grey_light"
            style="height: 50px"
            @click="selectOption(option)"
          >
            View
          </button>
          <button
            class="md:ml-8 md:mr-0 bg-grey_dark text-white w-12 md:w-32 hover:bg-grey_light"
            style="height: 50px"
            @click="editOption(option)"
          >
            Edit
          </button>
        </div>
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
