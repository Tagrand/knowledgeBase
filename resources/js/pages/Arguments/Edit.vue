<template>
  <div>
    <h1>{{ politicalArgument.reason }}</h1>

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
