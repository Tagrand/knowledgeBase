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
        Edit
      </router-link>
      <span>|</span>
      <router-link
        to="/sources"
        class="hover:text-blue-300"
      >
        All
      </router-link>
    </div>

    <div
      v-show="source.summary || source.link"
      class="bg-grey w-full p-2 px mb-4"
    >
      <p>{{ source.summary }}</p>
      <p class="text-right">
        <a
          class="text-blue-700 hover:text-blue-400 text-center"
          :href="source.link"
          target="blank"
          rel="noopener noreferrer"
        >view source</a>
      </p>
    </div>

    <div class="md:flex w-full justify-between">
      <info-box-vue
        title="Authors"
        type="authors"
        class="bg-grey md:w-9/20 pb-2"
        :collection="authors"
        info-name="first_name"
        extra-info="last_name"
      />

      <div class="bg-grey md:w-9/20 pb-2">
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

    <div class="md:flex w-full justify-between mt-6">
      <info-box-vue
        v-show="facts.length !== 0"
        title="Facts"
        type="facts"
        class="bg-grey md:w-9/20 pb-2"
        :collection="facts"
        info-name="claim"
      />

      <info-box-vue
        v-show="politicalArguments.length !== 0"
        title="Arguments"
        type="arguments"
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
