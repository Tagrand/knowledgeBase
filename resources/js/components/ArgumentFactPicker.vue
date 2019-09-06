<template>
  <div>
    <div>
      <h1 class="text-xl font-bold">
        Facts
      </h1>

      <div
        class="overflow-y-auto flex flex-wrap"
        style="max-height: 100px"
      >
        <div
          v-for="fact in argumentFacts"
          :key="fact.claim"
          class="mr-8"
          :class="fact.pivot.is_supportive ? 'text-green-700' : 'text-red-700'"
        >
          <span v-text="fact.claim" />
          <span
            v-show="!fact.pivot.is_supportive"
            @click="setFact(fact, true)"
          >Set for</span>
          <span
            v-show="fact.pivot.is_supportive"
            @click="setFact(fact, false)"
          >Set against</span>
        </div>

        <div
          v-for="fact in unrelatedFacts"
          :key="fact.claim"
          class="mr-8"
        >
          <span v-text="fact.claim" />
          <span @click="setFact(fact, true)">Set for</span>
          <span @click="setFact(fact, false)">Set against</span>
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
    politicalArgument: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      argumentFacts: [],
    };
  },

  computed: {
    facts() {
      return this.$store.state.facts;
    },

    unrelatedFacts() {
      return _.differenceBy(this.facts, this.argumentFacts, 'id');
    },

    noFact() {
      return _.isEmpty(this.politicalArgument);
    },
  },

  watch: {
    politicalArgument() {
      if (this.noFact) {
        this.argumentFacts = [];
        return;
      }

      axios
        .get(`/api/v1/arguments/${this.politicalArgument.id}/facts`)
        .then(({ data }) => { this.argumentFacts = data; })
        .catch((error) => console.log(error));
    },
  },

  created() {
    this.$store.dispatch('getFacts');

    axios
      .get(`/api/v1/arguments/${this.politicalArgument.id}/facts`)
      .then(({ data }) => { this.argumentFacts = data; })
      .catch((error) => console.log(error));
  },

  methods: {
    // eslint-disable-next-line camelcase
    setFact(fact, is_supportive) {
      axios
        .post(`/api/v1/arguments/${this.politicalArgument.id}/facts/${fact.id}/`, { is_supportive })
        .then(() => this.argumentFacts.push(fact))
        .catch((error) => console.log(error));
    },

    unsetFact(fact) {
      console.log(fact);
    },
  },
};
</script>
