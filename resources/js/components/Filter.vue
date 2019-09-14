<template>
  <div>
    <div
      v-for="item in selected"
      :key="`${item.id}${item[name]}`"
      @click="unSelect(item)"
    >
      <span class="font-bold hover:text-blue-400">
        {{ item[name] }}
      </span>
      <span>|</span>
    </div>
    <div
      v-for="(item, index) in unSelected"
      :key="`${item.id}${item[name]}`"
      @click="select(item)"
    >
      <span class="hover:text-blue-400">
        {{ item[name] }}
      </span>
      <span v-show="index + 1 !== unSelected.length">|</span>
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
    selected: {
      type: Array,
      required: true,
    },
    type: {
      type: String,
      required: true,
    },
    name: {
      default: 'name',
      type: String,
    },
  },

  computed: {
    unSelected() {
      return _.differenceBy(this.collection, this.selected, 'id');
    },
  },

  methods: {
    unSelect(item) {
      const index = _.findIndex(this.selected,
        (selectedSource) => selectedSource.id === item.id);
      this.selected.splice(index);
    },

    select(item) {
      this.selected.push(item);
    },
  },
};
</script>
