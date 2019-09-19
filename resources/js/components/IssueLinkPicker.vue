<template>
  <div>
    <h2 class="font-headline text-center text-2xl font-bold  pt-2">
      {{ capitalize(type) }}s
    </h2>

    <div
      style="max-height: 200px"
      class="overflow-y-auto"
    >
      <div class="flex justify-between">
        <div class="w-9/20 text-center">
          <h3 class="text-1xl">
            Connected
          </h3>
          <div
            v-for="point in issuePoints"
            :key="point[baseRelationInfo.name]"
            class="text-green-700"
            @click="unsetPoint(point)"
          >
            {{ point[baseRelationInfo.name] }}
          </div>
        </div>

        <div class="w-9/20 text-center">
          <h3 class="text-1xl">
            Rest
          </h3>
          <div
            v-for="point in unrelatedPoints"
            :key="point[baseRelationInfo.name]"
            @click="setPoint(point)"
          >
            {{ point[baseRelationInfo.name] }}
          </div>
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
    issue: {
      type: Object,
      required: true,
    },

    type: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      issuePoints: [],

      keys: {
        fact: {
          alias: 'fact',
          name: 'claim',
        },

        argument: {
          alias: 'politicalArgument',
          name: 'reason',
        },
      },

    };
  },

  computed: {
    baseRelationInfo() {
      return this.keys[this.type];
    },

    points() {
      return this.$store.state[`${this.baseRelationInfo.alias}s`];
    },

    unrelatedPoints() {
      return _.differenceBy(this.points, this.issuePoints, 'id');
    },

    noIssue() {
      return _.isEmpty(this.issue);
    },
  },

  watch: {
    issue() {
      if (this.noIssue) {
        this.issuePoints = [];
        return;
      }

      axios
        .get(`/api/v1/issues/${this.issue.id}/${this.type}s`)
        .then(({ data }) => { this.issuePoints = data; })
        .catch((error) => console.log(error));
    },
  },

  created() {
    this.$store.dispatch(`get${this.capitalize(this.type)}s`);
  },

  methods: {
    capitalize(s) {
      return s[0].toUpperCase() + s.slice(1);
    },

    setPoint(point) {
      axios
        .post(`/api/v1/${this.type}s/${point.id}/issues/${this.issue.id}`)
        .then(() => this.issuePoints.push(point))
        .catch((error) => console.log(error));
    },

    unsetPoint(point) {
      axios
        .delete(`/api/v1/${this.type}s/${point.id}/issues/${this.issue.id}`)
        .then(() => {
          const index = this.issuePoints.indexOf(point);
          this.issuePoints.splice(index, 1);
        })
        .catch((error) => console.log(error));
    },
  },
};
</script>
