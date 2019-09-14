<template>
  <div>
    <h1 class="font-headline text-center text-5xl font-bold">
      Source: {{ source.name }}
    </h1>
    <div class="text-center mb-4">
      <router-link
        :to="`/sources/${source.id}/edit`"
        class="hover:text-blue-300"
      >
        Edit |
      </router-link>
      <router-link
        to="/sources"
        class="hover:text-blue-300"
      >
        All
      </router-link>
    </div>

    <div
      v-show="source.summary"
      class="bg-grey w-full pb-2"
    >
      <p>{{ source.summary }}</p>
    </div>

    <div class="md:flex w-full justify-between">
      <info-box-vue
        title="Authors"
        class="bg-grey md:w-9/20 pb-2"
        :collection="authors"
        info-name="first_name"
        extra-info="last_name"
      />

      <div class="bg-grey md:w-9/20 pb-2">
        <h2 class="font-headline text-center text-2xl font-bold my-2">
          Related Issues
        </h2>
        <div
          v-for="(issue, index) in issues"
          :key="`${issue.id}${issue.name}`"
          class="text-center"
        >
          <span>{{ index + 1 }}.</span>
          <span>
            {{ issue.name }}
          </span>
        </div>
      </div>
    </div>

    <div class="md:flex w-full justify-between mt-6">
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
        extra-info="summary"
      />
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
      authors: [],
      facts: [],
      politicalArguments: [],
    };
  },

  computed: {
    source() {
      return this.$store.state.selectedSource;
    },

    issues() {
      const related = [];
      this.facts.forEach((fact) => {
        fact.issues.forEach((issue) => related.push(issue));
      });

      this.politicalArguments.forEach((argument) => {
        argument.issues.forEach((issue) => related.push(issue));
      });

      return _.uniqBy(related, 'id');
    },
  },

  created() {
    this.$store.dispatch('setSelectedSource', this.id);

    axios
      .get(`/api/v1/sources/${this.id}/authors`)
      .then(({ data }) => { this.authors = data; });

    axios
      .get(`/api/v1/sources/${this.id}/facts`)
      .then(({ data }) => { this.facts = data; });

    axios
      .get(`/api/v1/sources/${this.id}/arguments`)
      .then(({ data }) => { this.politicalArguments = data; });
  },
};
</script>
