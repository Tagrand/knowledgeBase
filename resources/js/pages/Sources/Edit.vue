<template>
  <div>
    <h1 class="font-headline text-center text-5xl font-bold">
      Source: {{ source.name }}
    </h1>

    <div class="text-center mb-4">
      <router-link
        :to="`/sources/${source.id}`"
        class="hover:text-blue-300"
      >
        View
      </router-link>
      <span>|</span>
      <router-link
        to="/sources"
        class="hover:text-blue-300"
      >
        All
      </router-link>
    </div>

    <div class="md:flex w-full justify-between">
      <div class="bg-grey md:w-9/20 pb-2">
        <p>{{ source.name }}</p>
        <p>{{ source.summary }}</p>
        <button
          class="w-24 ml-4 bg-grey_dark text-white hover:bg-grey_light"
          @click="save"
        >
          Save
        </button>
      </div>

      <author-picker-vue
        class="bg-grey md:w-9/20 pb-2"
        :source="source"
      />
    </div>

    <div class="md:flex w-full justify-between my-4">
      <div class="bg-grey md:w-9/20 p-4">
        <div class="flex">
          <div
            v-for="option in linkedOptions"
            :key="option.name"
          >
            <button
              :class="option.name === linkOption.name ? 'font-bold' : ''"
              class="hover:text-blue-300"
              @click="setOption(option)"
            >
              {{ option.name[0].toUpperCase() }}
            </button><span
              v-if="option.name === 'fact'"
              class="px-2"
            >|</span>
          </div>
        </div>

        <h2
          class="font-headline text-center text-2xl font-bold"
          :class="isOptionSelected ? 'pb-1' : 'pb-4'"
          style="margin-top: -1em"
        >
          Connected {{ linkOption.name }}
        </h2>

        <p
          v-show="isOptionSelected"
          class="cursor-pointer pb-4 text-center"
          @click="clearOption"
        >
          Reset
        </p>

        <select-vue
          v-if="!isOptionSelected"
          :data-type="linkOption.name"
          :search-key="linkOption.key"
          :collection="linkCollection"
          @save="saveOption"
          @select="setSelectedOption"
          @edit="editOption"
        />

        <div
          v-show="isOptionSelected"
          class="flex mb-4"
        >
          <p class="mr-4">
            {{ selectedOption[linkOption.key] }}
          </p>

          <p v-show="linkOption.name === 'argument'">
            {{ selectedOption.summary }}
          </p>
        </div>
      </div>

      <issue-picker-vue
        class="bg-grey md:w-9/20 pb-2"
        :parent-name="linkOption.name"
        :parent="selectedOption"
      />
    </div>
  </div>
</template>

<script>
import _ from 'lodash';
import axios from 'axios';
import SelectVue from '../../components/Select.vue';
import IssuePickerVue from '../../components/IssuePicker.vue';
import AuthorPickerVue from '../../components/AuthorPicker.vue';

export default {
  components: { SelectVue, IssuePickerVue, AuthorPickerVue },

  props: {
    id: {
      required: true,
      type: String,
    },
  },

  data() {
    return {
      linkedOptions: [
        {
          name: 'fact',
          key: 'claim',
        },
        {
          name: 'argument',
          key: 'reason',
        },
      ],

      linkOption:
      {
        name: 'fact',
        key: 'claim',
      },

      sourceFacts: [],

      sourceArguments: [],
      selectedArgument: {},
    };
  },

  computed: {
    selectedOption() {
      if (this.linkOption.name === 'fact') {
        return this.$store.state.selectedFact;
      }

      return this.selectedArgument;
    },

    isOptionSelected() {
      return !_.isEmpty(this.selectedOption);
    },

    source() {
      return this.$store.state.selectedSource;
    },

    linkCollection() {
      if (this.linkOption.name === 'fact') {
        return this.sourceFacts;
      }

      return this.sourceArguments;
    },
  },

  created() {
    this.$store.dispatch('setSelectedSource', this.id);
    this.$store.dispatch('getFacts');

    axios
      .get(`/api/v1/sources/${this.id}/facts`)
      .then(({ data }) => { this.sourceFacts = data; })
      .catch((error) => console.log(error));

    axios
      .get(`/api/v1/sources/${this.id}/arguments`)
      .then(({ data }) => { this.sourceArguments = data; })
      .catch((error) => console.log(error));
  },

  methods: {
    setSelectedOption(option) {
      if (this.linkOption.name === 'fact') {
        this.$store.commit('setSelectedFact', option.id);
      }

      this.selectedArgument = option;
    },

    saveOption(name) {
      const newOption = {};
      newOption[this.linkOption.key] = name;
      axios
        .post(`/api/v1/sources/${this.source.id}/${this.linkOption.name}s`, newOption)
        .then(({ data }) => {
          if (this.linkOption.name === 'fact') {
            this.sourceFacts.push(data);
            this.$store.commit('addFact', data);
            this.$store.commit('setSelectedFact', data.id);
          } else {
            this.sourceArguments.push(data);
            this.$store.commit('addArgument', data);
            this.selectedArgument = data;
          }
        });
    },

    editOption(option) {
      this.$router.push({ name: `${this.linkOption.name}.edit`, params: { id: option.id } });
    },

    clearOption() {
      this.$store.commit('clearSelectedFact');
      this.selectedArgument = {};
    },

    setOption(option) {
      this.linkOption = option;
    },

    save() {
      console.log();
    },
  },
};
</script>
