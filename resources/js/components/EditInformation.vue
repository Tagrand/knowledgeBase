<template>
  <div>
    <h2 class="font-headline text-center text-2xl font-bold  pt-2">
      {{ capitalize(type) }} Information
    </h2>

    <div class="flex mb-2">
      <label
        for="name"
        class="mx-2"
      >{{ capitalize(name) }}</label>
      <input
        :id="name"
        v-model="informationName"
        class="w-full mx-2 bg-grey_light pl-2"
        type="text"
      >
    </div>
    <div class="flex mb-2">
      <label
        for="summary"
        class="mx-2"
      >Summary:</label>
      <textarea
        id="summary"
        v-model="informationSummary"
        placeholder="summary"
        class="w-full mx-2 h-1/2 bg-grey_light pl-2"
      />
    </div>

    <div class="flex justify-end">
      <button
        class="w-16 ml-8 mr-2 bg-grey_dark text-white hover:bg-grey_light"
        style="height: 40px;"
        @click="save"
      >
        Save
      </button>
    </div>
  </div>
</template>
<script>
import axios from 'axios';

export default {
  props: {
    id: {
      required: true,
      type: String,
    },

    hasSummary: {
      required: true,
      type: Boolean,
    },

    type: {
      required: true,
      type: String,
    },

    name: {
      type: String,
      default: 'name',
    },

    summary: {
      default: '',
      type: String,
    },

    primaryInformation: {
      type: String,
      required: true,
    },
  },

  data() {
    return {
      informationName: '',
      informationSummary: '',
    };
  },

  watch: {
    primaryInformation() {
      this.informationName = this.primaryInformation;
      this.informationSummary = this.summary;
    },
  },

  created() {
    this.informationName = this.primaryInformation;
    this.informationSummary = this.summary;
  },

  methods: {
    capitalize(s) {
      return s[0].toUpperCase() + s.slice(1);
    },

    updatedInformation() {
      const information = {};

      if (this.hasSummary) {
        information.summary = this.informationSummary;
      }

      information[this.name] = this.informationName;

      return information;
    },

    save() {
      axios.patch(`/api/v1/${this.type}s/${this.id}`, this.updatedInformation()).then(({ data }) => {
        this.$store.commit(`updateSelected${this.capitalize(this.type)}`, data);
      });
    },
  },
};
</script>
