<template>
  <div>
    <h1 class="font-headline text-center text-5xl font-bold">
      Issue: {{ issue.name }}
    </h1>

    <div class="text-center mb-4">
      <router-link
        :to="`/issues/${issue.id}/edit`"
        class="hover:text-blue-300"
      >
        Edit
      </router-link>
      <span>|</span>
      <router-link
        to="/issues"
        class="hover:text-blue-300"
      >
        All
      </router-link>
    </div>

    <div
      v-if="issue.summary"
      class="bg-grey w-full mb-4"
    >
      <p>{{ issue.summary }}</p>
    </div>

    <div class="md:flex w-full justify-between">
      <info-box-vue
        title="Facts"
        class="bg-grey md:w-9/20 pb-2"
        :collection="facts"
        info-name="claim"
      />
      <info-box-vue
        title="Arguments"
        class="bg-grey md:w-9/20 pb-2"
        :collection="politicalArguments"
        info-name="reason"
      />
    </div>

    <div class="bg-grey pb-2">
      <h2 class="font-headline text-center text-2xl font-bold my-2">
        Related Issues
      </h2>
      <div class="flex justify-center px-4">
        <div
          v-for="(relatedIssue, index) in relatedIssues"
          :key="`${relatedIssue.id}${relatedIssue.name}`"
          class="text-center"
        >
          <router-link :to="`/issues/${relatedIssue.id}`">
            {{ relatedIssue.name }}
          </router-link>
          <span v-show="index + 1 !== relatedIssues.length">|</span>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import _ from 'lodash';
import axios from 'axios';
import InfoBoxVue from '../../components/InfoBox.vue';

export default {
  components: { InfoBoxVue },

  props: {
    id: {
      required: true,
      type: String,
    },
  },

  data() {
    return {
      facts: [],
      politicalArguments: [],
    };
  },

  computed: {
    issue() {
      return this.$store.state.selectedIssue;
    },

    relatedIssues() {
      const issues = _.map(this.politicalArguments,
        (politicalArgument) => _.filter(politicalArgument.issues,
          (issue) => issue.id !== this.issue.id));

      return _.uniqBy(_.flatten(issues), 'id');
    },
  },

  watch: {
    id() {
      this.resetIssue();
    },
  },

  created() {
    this.resetIssue();
  },

  methods: {
    resetIssue() {
      this.$store.dispatch('setSelectedIssue', this.id);

      axios
        .get(`/api/v1/issues/${this.id}/facts`)
        .then(({ data }) => { this.facts = data; });

      axios
        .get(`/api/v1/issues/${this.id}/arguments`)
        .then(({ data }) => { this.politicalArguments = data; });
    },
  },
};
</script>
