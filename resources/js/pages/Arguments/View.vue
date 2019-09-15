<template>
  <div>
    <h1 class="font-headline text-center text-5xl font-bold">
      Argument: {{ politicalArgument.reason }}
    </h1>

    <div
      class="text-center"
      :class="politicalArgument.summary ? 'mb-2' : 'mb-2'"
    >
      <router-link
        :to="`/arguments/${politicalArgument.id}/edit`"
        class="hover:text-blue-300"
      >
        Edit
      </router-link>
      <span>|</span>
      <router-link
        to="/arguments"
        class="hover:text-blue-300"
      >
        All
      </router-link>
    </div>
    <div
      v-if="!politicalArgument.summary"
      class="text-center mb-4"
    >
      Source: {{ source.name }}
    </div>

    <div
      v-else
      class="bg-grey w-full pb-2"
    >
      <p>{{ politicalArgument.summary }}</p>
      <p>Source: {{ source.name }}</p>
    </div>

    <div class="md:flex w-full justify-between">
      <info-box-vue
        title="For"
        class="bg-grey md:w-9/20 pb-2"
        :collection="factsFor"
        info-name="claim"
      />
      <info-box-vue
        title="Against"
        class="bg-grey md:w-9/20 pb-2"
        :collection="factsAgainst"
        info-name="claim"
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
          <span>{{ issue.name }}</span>
          <span v-show="index + 1 !== issues.length">|</span>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
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
      issues: [],
      facts: [],
    };
  },

  computed: {
    politicalArgument() {
      return this.$store.state.selectedPoliticalArgument;
    },

    source() {
      return this.$store.state.selectedSource;
    },

    factsFor() {
      return this.facts.filter((fact) => fact.pivot.is_supportive);
    },

    factsAgainst() {
      return this.facts.filter((fact) => !fact.pivot.is_supportive);
    },
  },

  created() {
    this.$store.dispatch('setSelectedPoliticalArgument', this.id)
      .then(() => {
        this.$store.dispatch('setSelectedSource', this.politicalArgument.source_id);
      });

    axios
      .get(`/api/v1/arguments/${this.id}/issues`)
      .then(({ data }) => { this.issues = data; })
      .catch((error) => console.log(error));

    axios
      .get(`/api/v1/arguments/${this.id}/facts`)
      .then(({ data }) => { this.facts = data; })
      .catch((error) => console.log(error));
  },
};
</script>
