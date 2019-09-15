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

    <div class="flex justify-between">
      <button
        v-for="option in linkedOptions"
        :key="option.name"
        :class="option.name === linkOption.name ? 'font-bold' : ''"
        @click="setOption(option)"
      >
        {{ option.name[0] }}
      </button>

      <div>
        <h1 class="text-xl font-bold">
          Connected {{ linkOption.name }}
        </h1>

        <search-vue
          v-show="linkOption.name === 'argument' && !isOptionSelected"
          :data-type="argument"
          :search-key="reason"
          :collection="sourceArguments"
          style="max-height: 200px; overflow-y: auto;"
          @argument-save="saveArgument"
          @argument-select="setSelectedArgument"
          @argument-edit="editArgument"
        />

        <search-vue
          v-show="linkOption.name === 'fact' && !isOptionSelected"
          data-type="fact"
          search-key="claim"
          :collection="sourceFacts"
          style="max-height: 200px; overflow-y: auto;"
          @fact-save="saveFact"
          @fact-select="setSelectedFact"
          @fact-edit="editFact"
        />

        <div v-show="isOptionSelected">
          <div class="flex mb-4">
            <p class="mr-4">
              {{ selectedOption[linkOption.key] }}
            </p>

            <router-link :to="`/${linkOption.name}s/${selectedOption.id}/edit`">
              Edit
            </router-link>

            <button @click="clearOption">
              Reset
            </button>
          </div>
        </div>

        <issue-picker-vue
          :parent-name="linkOption.name"
          :parent="selectedOption"
        />
      </div>
    </div>
  </div>
</template>

<script>
import _ from 'lodash';
import axios from 'axios';
import SearchVue from '../../components/Search.vue';
import IssuePickerVue from '../../components/IssuePicker.vue';
import AuthorPickerVue from '../../components/AuthorPicker.vue';

export default {
  components: { SearchVue, IssuePickerVue, AuthorPickerVue },

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
    setSelectedFact(fact) {
      console.log(fact.id);
      this.$store.commit('setSelectedFact', fact.id);
    },

    saveFact(claim) {
      axios
        .post(`/api/v1/sources/${this.source.id}/facts`, { claim })
        .then(({ data }) => {
          this.sourceFacts.push(data);
          this.$store.commit('addFact', data);
          this.$store.commit('setSelectedFact', data.id);
        });
    },

    editFact(fact) {
      this.$router.push({ name: 'facts.edit', params: { id: fact.id } });
    },

    clearSelectedOption() {
      this.$store.commit('clearSelectedFact');
      this.selectedArgument = {};
    },

    setSelectedArgument(argument) {
      this.selectedArgument = argument;
    },

    saveArgument(reason) {
      axios
        .post('/api/v1/arguments', {
          reason,
          source_id: this.source.id,
        })
        .then(({ data }) => {
          this.sourceArguments.push(data);
          this.$store.commit('addArgument', data);
          this.selectedArgument = data;
        });
    },

    editArgument() {
      console.log();
    },

    save() {
      console.log();
    },

    setOption(option) {
      this.linkOption = option;
    },
  },
};
</script>
