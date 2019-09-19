<template>
  <div>
    <h1 class="font-headline text-center text-5xl font-bold">
      Fact: {{ fact.claim }}
    </h1>

    <div class="text-center mb-4">
      <router-link
        :to="`/facts/${fact.id}`"
        class="hover:text-blue-300"
      >
        View
      </router-link>
      <span>|</span>
      <router-link
        to="/facts"
        class="hover:text-blue-300"
      >
        All
      </router-link>
    </div>

    <div class="md:flex w-full justify-between">
      <div class="bg-grey md:w-9/20 pb-2 md:mb-0 mb-4 pb-2">
        <edit-information-vue
          :id="id"
          :has-summary="false"
          type="fact"
          :primary-information="fact.claim"
          name="claim"
        />

        <p class="mx-2 pb-2">
          Current Source: {{ source.name }}
        </p>

        <select-vue
          v-if="showSourceSelect"
          class="mx-2"
          data-type="source"
          search-key="name"
          :collection="sources"
          :show-save="false"
          @select="setSource"
          @edit="editSource"
        />

        <div
          class="flex justify-end"
          :class="showSourceSelect ? 'mt-2' : ''"
        >
          <button
            class="w-16 ml-8 mr-2 bg-grey_dark text-white hover:bg-grey_light"
            style="height: 40px;"
            @click="showSourceSelect = !showSourceSelect"
          >
            {{ showSourceSelect ? 'Hide' : 'Edit' }}
          </button>
        </div>
      </div>

      <issue-picker-vue
        :parent="fact"
        parent-name="fact"
        class="bg-grey md:w-9/20 pb-2"
      />
    </div>

    <argument-fact-picker-vue
      other-relation="argument"
      type="fact"
      class="w-full bg-grey py-2 my-4"
    />
  </div>
</template>
<script>
import axios from 'axios';
import SelectVue from '../../components/Select.vue';
import IssuePickerVue from '../../components/IssuePicker.vue';
import EditInformationVue from '../../components/EditInformation.vue';
import ArgumentFactPickerVue from '../../components/ArgumentFactPicker.vue';

export default {
  components: {
    IssuePickerVue, EditInformationVue, SelectVue, ArgumentFactPickerVue,
  },

  props: {
    id: {
      required: true,
      type: String,
    },
  },

  data() {
    return {
      claim: '',

      source: {},

      showSourceSelect: false,
    };
  },

  computed: {
    fact() {
      return this.$store.state.selectedFact;
    },

    sources() {
      return this.$store.state.sources;
    },
  },

  watch: {
    id() {
      this.claim = this.fact.claim;
    },

    'fact.source': {
      handler() {
        this.source = this.fact.source;
      },
      deep: true,
    },
  },

  created() {
    this.$store.dispatch('getSources');
    this.$store.dispatch('setSelectedFact', this.id)
      .then(() => {
        this.claim = this.fact.claim;
      });
  },

  methods: {
    save() {
      axios
        .patch(`/api/v1/facts/${this.fact.id}`, {
          claim: this.claim,
        })
        .then(({ data }) => this.$store.commit('updateSelectedFact', data));
    },

    setSource(source) {
      axios.patch(`/api/v1/facts/${this.id}`, {
        source_id: source.id,
      }).then(() => {
        this.fact.source = source;
        this.$store.commit('updateSelectedFact', this.fact);
        this.$store.commit('updateSelectedSource', source.id);
        this.showSourceSelect = false;
      });
    },

    editSource(source) {
      this.$router.push({ name: 'source.edit', params: { id: source.id } });
    },
  },
};
</script>
