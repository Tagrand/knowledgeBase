<template>
  <div>
    <h1 class="font-headline text-center text-5xl font-bold">
      Fact: {{ fact.claim }}
    </h1>

    <div class="text-center mb-4">
      <router-link
        :to="`/facts/${fact.id}`"
        class="hover:text-blue-300"
      >
        View
      </router-link>
      <span>|</span>
      <router-link
        to="/facts"
        class="hover:text-blue-300"
      >
        All
      </router-link>
    </div>

    <div class="md:flex w-full justify-between">
      <edit-information-vue
        :id="id"
        :has-summary="false"
        type="fact"
        :primary-information="fact.name"
        name="claim"
        class="bg-grey md:w-9/20 pb-2 md:mb-0 mb-4 pb-2"
      />

      <issue-picker-vue
        :parent="fact"
        parent-name="fact"
        class="bg-grey md:w-9/20 pb-2"
      />
    </div>
  </div>
</template>
<script>
import axios from 'axios';
import IssuePickerVue from '../../components/IssuePicker.vue';
import EditInformationVue from '../../components/EditInformation.vue';

export default {
  components: { IssuePickerVue, EditInformationVue },

  props: {
    id: {
      required: true,
      type: String,
    },
  },

  data() {
    return {
      claim: '',
    };
  },

  computed: {
    fact() {
      return this.$store.state.selectedFact;
    },
  },

  watch: {
    id() {
      this.claim = this.fact.claim;
    },
  },

  created() {
    this.$store.dispatch('setSelectedFact', this.id)
      .then(() => {
        this.claim = this.fact.claim;
      });
  },

  methods: {
    save() {
      axios
        .patch(`/api/v1/facts/${this.fact.id}`, {
          claim: this.claim,
        })
        .then(({ data }) => this.$store.commit('updateSelectedFact', data));
    },
  },
};
</script>
