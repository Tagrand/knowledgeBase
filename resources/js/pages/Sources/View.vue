<template>
  <div>
    <h1 class="font-headline text-center text-5xl font-bold">
      Source: {{ source.name }}
    </h1>
    <div class="text-center">
      <router-link :to="`/sources/${source.id}/edit`">
        Edit |
      </router-link>
      <router-link to="/sources">
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
      <div class="bg-grey md:w-9/20 pb-2">
        <h2 class="font-headline text-center text-2xl font-bold my-2">
          Authors
        </h2>
        <div
          v-for="(author, index) in authors"
          :key="`${author.id}${author.first_name}`"
          class="text-center"
        >
          <span>{{ index + 1 }}.</span>
          <span>
            {{ author.first_name }}
            {{ author.last_name }}
          </span>
        </div>
      </div>
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
      <div class="bg-grey md:w-9/20 pb-2">
        <h2 class="font-headline text-center text-2xl font-bold my-2">
          Facts
        </h2>
        <div
          v-for="(fact, index) in facts"
          :key="`${fact.id}${fact.claim}`"
          class="text-center"
        >
          <span>{{ index + 1 }}.</span>
          <span>
            {{ fact.claim }}
          </span>
        </div>
      </div>

      <div class="bg-grey md:w-9/20 pb-2">
        <h2 class="font-headline text-center text-2xl font-bold my-2">
          Arguments
        </h2>
        <div
          v-for="(politicalArgument, index) in politicalArguments"
          :key="`${politicalArgument.id}${politicalArgument.reason}`"
          class="text-center"
        >
          <span>{{ index + 1 }}.</span>
          <span>
            {{ politicalArgument.reason }}
            {{ politicalArgument.summary }}
          </span>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import _ from 'lodash';
import axios from 'axios';

export default {
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
