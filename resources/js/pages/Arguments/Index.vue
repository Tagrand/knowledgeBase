<template>
  <div>
    <search-vue
      class="text-blue"
      search-key="reason"
      data-type="argument"
      :collection="politicalArguments"
      @argument-save="saveArgument"
      @argument-edit="editArgument"
      @argument-select="selectArgument"
    />

    <router-link to="/sources">
      Create new Argument (create a source first)
    </router-link>
  </div>
</template>

<script>
import SearchVue from '../../components/Search.vue';

export default {
  components: { SearchVue },

  computed: {
    politicalArguments() {
      return this.$store.state.arguments;
    },
  },

  created() {
    this.$store.dispatch('getArguments');
  },

  methods: {
    saveArgument(claim) {
      console.log(claim);
    },

    selectArgument(argument) {
      console.log(argument);
    },

    editArgument(argument) {
      console.log(argument);
    },
  },
};
</script>
