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
          v-for="fact in factsFor"
          :key="fact.claim"
          class="mr-8 text-green-700"
        >
          <span v-text="fact.claim" />
          <span @click="updateFact(fact, false)">Set against</span>
          <span @click="deleteFact(fact)">Remove</span>
        </div>

        <div
          v-for="fact in factsAgainst"
          :key="fact.claim"
          class="mr-8 text-red-700"
        >
          <span v-text="fact.claim" />
          <span @click="deleteFact(fact)">Remove</span>
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
/* eslint-disable camelcase */
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
      factsFor: [],
      factsAgainst: [],
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

    this.$nextTick(() => {
      console.log(this.politicalArgument);
      axios
        .get(`/api/v1/arguments/${this.politicalArgument.id}/facts`)
        .then(({ data }) => {
          this.argumentFacts = data;
          this.sortFactsBySupport();
        })
        .catch((error) => console.log(error));
    });
  },

  methods: {
    setFact(fact, is_supportive) {
      axios
        .post(`/api/v1/arguments/${this.politicalArgument.id}/facts/${fact.id}/`, { is_supportive })
        .then(() => {
          this.argumentFacts.push(fact);
          this.sortFactsBySupport();
        })
        .catch((error) => console.log(error));
    },

    updateFact(fact, is_supportive) {
      axios
        .patch(`/api/v1/arguments/${this.politicalArgument.id}/facts/${fact.id}/`, { is_supportive })
        .then(() => {
          // eslint-disable-next-line no-param-reassign
          fact.pivot.is_supportive = is_supportive;
          this.sortFactsBySupport();
        })
        .catch((error) => console.log(error));
    },

    deleteFact(fact) {
      axios
        .delete(`/api/v1/arguments/${this.politicalArgument.id}/facts/${fact.id}/`)
        .then(() => {
          const index = this.argumentFacts.indexOf(fact);
          this.argumentFacts.splice(index, 1);
          this.sortFactsBySupport();
        })
        .catch((error) => console.log(error));
    },

    sortFactsBySupport() {
      this.factsFor = [];
      this.factsAgainst = [];
      this.argumentFacts.forEach((fact) => {
        // eslint-disable-next-line no-unused-expressions
        fact.pivot.is_supportive ? this.factsFor.push(fact) : this.factsAgainst.push(fact);
      });
    },
  },
};
</script>
