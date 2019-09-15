<template>
  <div>
    <h1 class="font-headline text-center text-5xl font-bold">
      Fact: {{ fact.claim }}
    </h1>

    <div class="text-center mb-2">
      <router-link
        :to="`/facts/${fact.id}/edit`"
        class="hover:text-blue-300"
      >
        Edit
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
      <info-box-vue
        title="Arguments For"
        type="arguments"
        class="bg-grey md:w-9/20 pb-2"
        :collection="argumentsFor"
        info-name="reason"
        extra-info="summary"
      />
      <info-box-vue
        title="Arguments Against"
        type="arguments"
        class="bg-grey md:w-9/20 pb-2"
        :collection="argumentsAgainst"
        info-name="reason"
        extra-info="summary"
      />
    </div>
    <div class="bg-grey pb-2">
      <h2 class="font-headline text-center text-2xl font-bold my-2">
        Related Issues
      </h2>
      <div class="flex justify-center px-4">
        <div
          v-for="(issue, index) in issues"
          :key="`${issue.id}${issue.name}`"
          class="text-center"
        >
          <router-link :to="`/issues/${issue.id}`">
            {{ issue.name }}
          </router-link>
          <span v-show="index + 1 !== issues.length">|</span>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import axios from 'axios';
import _ from 'lodash';
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
      issues: [],
      politicalArguments: [],
    };
  },

  computed: {
    fact() {
      return this.$store.state.selectedFact;
    },

    source() {
      return this.$store.state.selectedSource;
    },

    argumentsFor() {
      return _.filter(this.politicalArguments,
        (politicalArgument) => politicalArgument.pivot.is_supportive);
    },

    argumentsAgainst() {
      return _.filter(this.politicalArguments,
        (politicalArgument) => !politicalArgument.pivot.is_supportive);
    },
  },

  created() {
    this.$store.dispatch('setSelectedFact', this.id)
      .then(() => this.$store.dispatch('setSelectedSource', this.fact.source_id));

    axios
      .get(`/api/v1/facts/${this.id}/issues`)
      .then(({ data }) => { this.issues = data; })
      .catch((error) => console.log(error));

    axios
      .get(`/api/v1/facts/${this.id}/arguments`)
      .then(({ data }) => { this.politicalArguments = data; })
      .catch((error) => console.log(error));
  },
};
</script>
