<template>
  <div>
    <input type="text" v-bind:placeholder="placeholder" v-model="search" />
    <button @click="saveOption" v-show="isNewOption">Save as new</button>

    <div>
      <div
        @click="selectOption(option)"
        v-bind:key="option[defaultKey]"
        v-for="option in filteredCollection"
      >{{option[defaultKey]}}</div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
      collection: {
          type: Array, 
          required: true,
      },

      defaultKey: {
          type: String,
          default: 'name',
      },

      placeholder: {
          type: String,
          required: true,
      }
  },

  data() {
    return {
      search: '',
    };
  },


  computed: {
    filteredCollection() {
      if (this.search === "") {
        return this.collection;
      }

      return _.filter(this.collection, option => {
        return option[this.defaultKey]
          .toLowerCase()
          .includes(this.search.toLowerCase());
      });
    },

    isNewOption() {
      return !_.some(this.collection, option => {
        return option[this.defaultKey].toLowerCase() === this.search.toLowerCase();
      });
    },
  },

  methods:{
      selectOption(option) {
          this.search = '';
          this.$emit(`${this.placeholder}-select`, option);
      },

      saveOption() {
          this.search = '';
          this.$emit(`${this.placeholder}-save`, this.search);
      }
  }
};
</script>