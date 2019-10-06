<template>
  <div>
    <h1 class="font-headline text-center text-5xl font-bold">
      Source: {{ source.name }}
    </h1>

    <div class="text-center  md:mb-4 mb-8">
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
      <edit-information-vue
        :id="id"
        :has-summary="true"
        :has-link="true"
        type="source"
        :primary-information="source.name"
        :summary="source.summary"
        :link="source.link"
        class="bg-grey md:w-9/20 pb-2 md:mb-0 mb-4 pb-2"
      />

      <author-picker-vue
        class="bg-grey md:w-9/20 pb-2"
        :source="source"
      />
    </div>

    <div class="md:flex w-full justify-between my-4">
      <div class="bg-grey md:w-9/20 p-4 md:mb-0 mb-4">
        <h2
          class="font-headline text-center text-2xl font-bold"
          :class="isFactSelected ? 'mb-2' : 'mb-4'"
          v-text="isFactSelected ? 'Fact: ' + selectedFact.claim : 'Select Fact'"
        />

        <div v-if="isFactSelected">
          <p
            class="cursor-pointer pb-4 text-center hover:text-blue-300"
            @click="clearFact"
          >
            Reset
          </p>
        </div>

        <select-vue
          v-else
          data-type="fact"
          search-key="claim"
          :collection="sourceFacts"
          @save="saveFact"
          @select="setSelectedFact"
          @edit="editFact"
        />
      </div>

      <issue-picker-vue
        class="bg-grey md:w-9/20 pb-2"
        parent-name="fact"
        :parent="selectedFact"
      />
    </div>

    <div class="md:flex w-full justify-between my-4">
      <div class="bg-grey md:w-9/20 p-4 md:mb-0 mb-4">
        <div v-if="isArgumentSelected">
          <edit-information-vue
            :id="id"
            :has-summary="true"
            name="reason"
            type="argument"
            :primary-information="selectedArgument.reason"
            :summary="selectedArgument.summary"
          />
          <p
            class="cursor-pointer pb-4 text-center hover:text-blue-300"
            @click="clearArgument"
          >
            Reset
          </p>
        </div>
        <div v-else>
          <h2 class="font-headline text-center text-2xl font-bold mb-4">
            Select Argument
          </h2>

          <select-vue
            data-type="argument"
            search-key="reason"
            :collection="sourceArguments"
            @save="saveArgument"
            @select="setSelectedArgument"
            @edit="editArgument"
          />
        </div>
      </div>

      <issue-picker-vue
        class="bg-grey md:w-9/20 pb-2"
        parent-name="fact"
        :parent="selectedFact"
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
import EditInformationVue from '../../components/EditInformation.vue';

export default {
  components: {
    SelectVue, IssuePickerVue, AuthorPickerVue, EditInformationVue,
  },

  props: {
    id: {
      required: true,
      type: String,
    },
  },

  data() {
    return {
      sourceFacts: [],

      sourceArguments: [],

      name: '',
      summary: '',
    };
  },

  computed: {
    selectedFact() {
      return this.$store.state.selectedFact;
    },

    isFactSelected() {
      return !_.isEmpty(this.$store.state.selectedFact);
    },

    selectedArgument() {
      return this.$store.state.selectedPoliticalArgument;
    },

    isArgumentSelected() {
      return !_.isEmpty(this.$store.state.selectedPoliticalArgument);
    },

    source() {
      return this.$store.state.selectedSource;
    },
  },

  watch: {
    id() {
      this.resetSource();
    },
  },

  created() {
    this.resetSource();
  },

  methods: {
    setSelectedFact(fact) {
      this.$store.commit('setSelectedFact', fact.id);
    },

    setSelectedArgument(politicalArgument) {
      this.$store.commit('setSelectedPoliticalArgument', politicalArgument.id);
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

    saveArgument(reason) {
      axios
        .post(`/api/v1/sources/${this.source.id}/arguments`, { reason })
        .then(({ data }) => {
          this.sourceArguments.push(data);
          this.$store.commit('addArgument', data);
          this.$store.commit('setPoliticalArgument', data.id);
        });
    },

    editFact(fact) {
      this.$router.push({ name: 'facts.edit', params: { id: fact.id } });
    },

    editArgument(politicalArgument) {
      this.$router.push({ name: 'arguments.edit', params: { id: politicalArgument.id } });
    },

    clearFact() {
      this.$store.commit('clearSelectedFact');
    },

    clearArgument() {
      this.$store.commit('clearSelectedPoliticalArgument');
    },

    resetSource() {
      this.$store.dispatch('setSelectedSource', this.id).then(() => {
        this.name = this.source.name;
        this.summary = this.source.summary;
      });
      this.$store.dispatch('getFacts');
      this.$store.dispatch('getArguments');

      axios
        .get(`/api/v1/sources/${this.id}/facts`)
        .then(({ data }) => { this.sourceFacts = data; })
        .catch((error) => console.log(error));

      axios
        .get(`/api/v1/sources/${this.id}/arguments`)
        .then(({ data }) => { this.sourceArguments = data; })
        .catch((error) => console.log(error));
    },
  },
};
</script>
