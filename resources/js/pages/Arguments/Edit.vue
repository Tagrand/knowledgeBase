<template>
  <div>
    <h1>{{ politicalArgument.reason }}</h1>

    <input
      v-model="reason"
      type="text"
    >
    <button @click="save">
      Save
    </button>

    <issue-picker-vue
      :parent="politicalArgument"
      parent-name="argument"
    />

    <router-link :to="`/arguments/${id}`">
      View
    </router-link>
  </div>
</template>
<script>
import axios from 'axios';
import IssuePickerVue from '../../components/IssuePicker.vue';

export default {
  components: { IssuePickerVue },

  props: {
    id: {
      required: true,
      type: String,
    },
  },

  data() {
    return {
      reason: '',
    };
  },

  computed: {
    politicalArgument() {
      return this.$store.state.selectedPoliticalArgument;
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
      });
  },

  methods: {
    save() {
      axios
        .patch(`/api/v1/arguments/${this.id}`, {
          reason: this.reason,
        })
        .then(({ data }) => this.$store.commit('updateSelectedArgument', data));
    },
  },
};
</script>
