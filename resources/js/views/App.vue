<template>
  <div>
    <div class="flex text-gray-800 pb-4 justify-between pt-2 px-8 text-md">
      <div class="flex">
        <div
          v-for="(link, index) in links"
          :key="link.name"
        >
          <router-link
            class="hover:text-blue-400 text-weight-300"
            style="font-size: 18px;"
            :class="shouldUnderline(link) ? 'highlight' : ''"
            :to="link.route"
            v-text="link.name"
          >
            link.name
          </router-link><span v-show="index + 1 !== links.length" class="px-2">|</span>
        </div>
      </div>
      <div>
        <a
          href="/logout"
          class="hover:text-blue-400 text-weight-300"
          style="font-size: 18px;"
          onclick="event.preventDefault();document.getElementById('logout-form').submit();"
        >
          Log out
        </a>
      </div>
    </div>
    <router-view class="px-8" />
  </div>
</template>

<style scoped>
.highlight {
  border-bottom: 2px solid currentColor;
}
</style>

<script>
export default {
  data() {
    return {
      links: [
        {
          name: 'Home',
          route: '/',
        },
        {
          name: ' Sources',
          route: '/sources',
        },
        {
          name: ' Facts',
          route: '/facts',
        },
        {
          name: ' Arguments',
          route: '/arguments',
        },
        {
          name: ' Issues',
          route: '/issues',
        },
      ],
    };
  },

  methods: {
    shouldUnderline(link) {
      return this.$route.path.indexOf(link.name.toLowerCase().trim()) !== -1;
    },
  },
};
</script>
