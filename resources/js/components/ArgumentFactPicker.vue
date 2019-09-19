<template>
  <div>
    <div>
      <h2 class="font-headline text-center text-2xl font-bold pt-2">
        {{ capitalize(type) }}s
      </h2>

      <div
        class="w-full flex justify-between px-2"
        style="max-height: 500px overflow-y-scroll"
      >
        <div class="w-1/3">
          <h2 class="text-center text-xl pt-2">
            {{ capitalize(otherRelation) }}s For
          </h2>

          <div
            v-for="point in pointsFor"
            :key="point[otherRelationInfo.name]"
            class="text-green-700 text-center"
          >
            <span v-text="point[otherRelationInfo.name]" />
            <span
              class="cursor-pointer hover:text-blue-300"
              @click="updatePoint(point, false)"
            >Set against</span>
            <span
              class="cursor-pointer hover:text-blue-300"
              @click="unlinkPoint(point)"
            >Remove</span>
          </div>
        </div>

        <div class="w-1/3">
          <h2 class="text-center text-xl pt-2">
            {{ capitalize(otherRelation) }}s Against
          </h2>

          <div
            v-for="point in pointsAgainst"
            :key="point[otherRelationInfo.name]"
            class="text-red-700 text-center"
          >
            <span v-text="point[otherRelationInfo.name]" />
            <span
              class="cursor-pointer hover:text-blue-300"
              @click="updatePoint(point, true)"
            >Set for</span>
            <span
              class="cursor-pointer hover:text-blue-300"
              @click="unlinkPoint(point)"
            >Remove</span>
          </div>
        </div>

        <div class="w-1/3">
          <h2 class="text-center text-xl pt-2">
            Other {{ capitalize(otherRelation) }}s
          </h2>

          <div
            v-for="point in unrelatedPoints"
            :key="point[otherRelationInfo.name]"
            class="text-center"
          >
            <span v-text="point[otherRelationInfo.name]" />
            <span
              class="cursor-pointer hover:text-blue-300"
              @click="setPoint(point, true)"
            >Set for</span>
            <span
              class="cursor-pointer hover:text-blue-300"
              @click="setPoint(point, false)"
            >Set against</span>
          </div>
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
    type: {
      type: String,
      required: true,
    },

    otherRelation: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
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

      relatedPoints: [],
      pointsFor: [],
      pointsAgainst: [],
    };
  },

  computed: {
    baseRelation() {
      return this.$store.state[`selected${this.capitalize(this.baseRelationInfo.alias)}`];
    },

    baseRelationInfo() {
      return this.keys[this.type];
    },

    otherRelationInfo() {
      return this.keys[this.otherRelation];
    },

    points() {
      return this.$store.state[`${this.otherRelationInfo.alias}s`];
    },

    unrelatedPoints() {
      return _.differenceBy(this.points, this.relatedPoints, 'id');
    },

    noPoint() {
      return _.isEmpty(this.baseRelation);
    },
  },

  watch: {
    baseRelation() {
      if (this.noPoint) {
        this.relatedPoints = [];
        return;
      }

      axios
        .get(`/api/v1/${this.type}s/${this.baseRelation.id}/${this.otherRelation}s`)
        .then(({ data }) => {
          this.relatedPoints = data;
          this.sortPointsBySupport();
        })
        .catch((error) => console.log(error));
    },
  },

  created() {
    this.$store.dispatch(`get${this.capitalize(this.otherRelation)}s`);
  },

  methods: {
    capitalize(s) {
      return s[0].toUpperCase() + s.slice(1);
    },

    setPoint(point, is_supportive) {
      axios
        .post(this.rudUrl(point), { is_supportive })
        .then(() => {
          const newPoint = point;
          newPoint.pivot = {};
          newPoint.pivot.is_supportive = is_supportive;
          this.relatedPoints.push(newPoint);
          this.sortPointsBySupport();
        })
        .catch((error) => console.log(error));
    },

    updatePoint(point, is_supportive) {
      axios
        .patch(this.rudUrl(point), { is_supportive })
        .then(() => {
          // eslint-disable-next-line no-param-reassign
          point.pivot.is_supportive = is_supportive;
          this.sortPointsBySupport();
        })
        .catch((error) => console.log(error));
    },

    unlinkPoint(point) {
      axios
        .delete(this.rudUrl(point))
        .then(() => {
          const index = this.relatedPoints.indexOf(point);
          this.relatedPoints.splice(index, 1);
          this.sortPointsBySupport();
        })
        .catch((error) => console.log(error));
    },

    sortPointsBySupport() {
      this.pointsFor = [];
      this.pointsAgainst = [];
      this.relatedPoints.forEach((point) => {
        // eslint-disable-next-line no-unused-expressions
        point.pivot.is_supportive ? this.pointsFor.push(point) : this.pointsAgainst.push(point);
      });
    },

    rudUrl(point) {
      if (this.type === 'fact') {
        return `/api/v1/${this.otherRelation}s/${point.id}/${this.type}s/${this.baseRelation.id}`;
      }

      return `/api/v1/${this.type}s/${this.baseRelation.id}/${this.otherRelation}s/${point.id}`;
    },

  },
};
</script>
