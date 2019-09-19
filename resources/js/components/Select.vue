<template>
  <div>
    <div class="flex justify-between">
      <input
        v-model="search"
        type="text"
        class="w-4/5 pl-2 bg-grey_light"
        style="height: 40px"
        :placeholder="dataType"
      >
      <button
        class="w-16 ml-8 bg-grey_dark text-white hover:bg-grey_light"
        style="height: 40px; margin-right: 12px"
        @click="saveOption"
      >
        Save
      </button>
    </div>

    <div
      class="overflow-y-scroll mt-4"
      style="max-height: 200px"
    >
      <div
        v-for="option in filteredCollection"
        :key="option[searchKey]"
        class="flex justify-between pt-4 content-center"
      >
        <div
          style="overflow-x: auto"
          class="w-1/2 hover:text-blue-300 cursor-pointer"
          @click="selectOption(option)"
        >
          <span v-text="option[searchKey]" />
          <span
            v-show="!!option[extraInfo]"
            v-text="`(${option[extraInfo]})`"
          />
        </div>
        <div>
          <button
            class="bg-grey_dark text-white w-16 ml-3 hover:bg-grey_light"
            style="height: 40px"
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
      this.$emit('select', option);
      this.search = '';
    },

    saveOption() {
      this.$emit('save', this.search);
      this.search = '';
    },

    editOption(option) {
      this.$emit('edit', option);
      this.search = '';
    },
  },
};
</script>
