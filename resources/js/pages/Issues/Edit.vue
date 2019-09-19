<template>
  <div>
    <h1 class="font-headline text-center text-5xl font-bold">
      Issue: {{ issue.name }}
    </h1>

    <div class="text-center mb-4">
      <router-link
        :to="`/issues/${issue.id}`"
        class="hover:text-blue-300"
      >
        View
      </router-link>
      <span>|</span>
      <router-link
        to="/issues"
        class="hover:text-blue-300"
      >
        All
      </router-link>
    </div>

    <edit-information-vue
      :id="id"
      :has-summary="true"
      type="issue"
      :primary-information="issue.name"
      :summary="issue.summary"
      class="bg-grey w-full pb-2 md:mb-0 mb-4 pb-2"
    />

    <div class="md:flex w-full justify-between my-4">
      <div class="bg-grey md:w-9/20 pb-2 md:mb-0 mb-4 pb-2">
        <issue-link-picker-vue
          :issue="issue"
          type="fact"
        />
      </div>

      <div class="bg-grey md:w-9/20 pb-2 md:mb-0 mb-4 pb-2">
        <issue-link-picker-vue
          :issue="issue"
          type="argument"
        />
      </div>
    </div>
  </div>
</template>
<script>
import axios from 'axios';
import EditInformationVue from '../../components/EditInformation.vue';
import IssueLinkPickerVue from '../../components/IssueLinkPicker.vue';

export default {
  components: { IssueLinkPickerVue, EditInformationVue },
  props: {
    id: {
      required: true,
      type: String,
    },
  },

  data() {
    return {
      name: '',
      summary: '',
    };
  },

  computed: {
    issue() {
      return this.$store.state.selectedIssue;
    },
  },

  watch: {
    id() {
      this.resetIssue();
    },
  },

  created() {
    this.resetIssue();
  },

  methods: {
    resetIssue() {
      this.$store.dispatch('setSelectedIssue', this.id).then(() => {
        this.name = this.issue.name;
        this.summary = this.issue.summary;
      });
    },

    save() {
      axios
        .patch(`/api/v1/issues/${this.issue.id}`, {
          name: this.name,
          summary: this.summary,
        })
        .then(({ data }) => {
          this.$store.commit('updateSelectedIssue', data);
        });
    },
  },
};
</script>
