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
      return this.$store.state.politicalArguments;
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
      this.$router.push({ name: 'arguments.view', params: { id: argument.id } });
    },

    editArgument(argument) {
      this.$router.push({ name: 'arguments.edit', params: { id: argument.id } });
    },
  },
};
</script>
