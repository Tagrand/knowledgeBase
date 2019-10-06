<template>
  <div>
    <h2 class="font-headline text-center text-2xl font-bold pt-2">
      {{ capitalize(type) }} Information
    </h2>

    <div class="flex mb-2">
      <label
        for="name"
        class="mx-2"
      >{{ capitalize(name) }}:</label>
      <input
        :id="name"
        v-model="informationName"
        class="w-full mx-2 bg-grey_light pl-2"
        type="text"
      >
    </div>
    <div
      v-if="hasSummary"
      class="flex mb-2"
    >
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

    <div
      v-if="hasLink"
      class="flex mb-2"
    >
      <label
        :for="linkName"
        class="mx-2"
      >{{ capitalize(linkName) }}:</label>
      <textarea
        :id="linkName"
        v-model="informationLink"
        :placeholder="linkName"
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

    hasLink: {
      type: Boolean,
      default: false,
    },

    type: {
      required: true,
      type: String,
    },

    primaryInformation: {
      required: true,
      type: String,
    },

    name: {
      type: String,
      default: 'name',
    },

    summary: {
      type: String,
      default: '',
    },

    link: {
      type: String,
      default: '',
    },

    linkName: {
      type: String,
      default: 'link',
    },
  },

  data() {
    return {
      informationName: '',
      informationSummary: '',
      informationLink: '',
    };
  },

  watch: {
    primaryInformation() {
      this.informationName = this.primaryInformation;
      this.informationSummary = this.summary;
      this.informationLink = this.link;
    },
  },

  created() {
    this.informationName = this.primaryInformation;
    this.informationSummary = this.summary;
    this.informationLink = this.link;
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

      if (this.hasLink) {
        information[this.linkName] = this.informationLink;
      }

      if (this.informationName !== this.primaryInformation) {
        information[this.name] = this.informationName;
      }

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
