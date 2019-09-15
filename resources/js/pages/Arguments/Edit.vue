<template>
  <div>
    <h1 class="font-headline text-center text-5xl font-bold">
      Argument: {{ politicalArgument.reason }}
    </h1>

    <div class="text-center mb-4">
      <router-link
        :to="`/arguments/${politicalArgument.id}`"
        class="hover:text-blue-300"
      >
        View
      </router-link>
      <span>|</span>
      <router-link
        to="/arguments"
        class="hover:text-blue-300"
      >
        All
      </router-link>
    </div>

    <input
      v-model="reason"
      type="text"
    >
    <input
      v-model="summary"
      type="text"
    >
    <button @click="save">
      Save
    </button>

    <issue-picker-vue
      :parent="politicalArgument"
      parent-name="argument"
    />

    <argument-fact-picker-vue :political-argument="politicalArgument" />

    <router-link :to="`/arguments/${id}`">
      View
    </router-link>
  </div>
</template>
<script>
import axios from 'axios';
import IssuePickerVue from '../../components/IssuePicker.vue';
import ArgumentFactPickerVue from '../../components/ArgumentFactPicker.vue';

export default {
  components: { IssuePickerVue, ArgumentFactPickerVue },

  props: {
    id: {
      required: true,
      type: String,
    },
  },

  data() {
    return {
      reason: '',
      summary: '',
    };
  },

  computed: {
    politicalArgument() {
      return this.$store.state.selectedPoliticalArgument;
    },

    reasonHasChanged() {
      return this.reason !== this.politicalArgument.reason;
    },
  },

  watch: {
    id() {
      this.reason = this.politicalArguement.reason;
    },
  },

  created() {
    this.$store.dispatch('setSelectedPoliticalArgument', this.id)
      .then(() => {
        this.reason = this.politicalArgument.reason;
        this.summary = this.politicalArgument.summary;
      });
  },

  methods: {
    save() {
      const changes = { summary: this.summary };
      if (this.reasonHasChanged) {
        changes.reason = this.reason;
      }
      axios
        .patch(`/api/v1/arguments/${this.id}`, changes)
        .then(({ data }) => this.$store.commit('updateSelectedPoliticalArgument', data));
    },
  },
};
</script>
