<template>
  <div>
    <div class="px-4">
      <div class="flex pb-4 justify-between pt-2 text-md">
        <router-link
          class="hover:text-blue-400"
          to="/"
        >
          Home
        </router-link>
        <router-link
          class="hover:text-blue-400"
          to="/sources"
        >
          Sources
        </router-link>
        <router-link
          class="hover:text-blue-400"
          to="/facts"
        >
          Facts
        </router-link>
        <router-link
          class="hover:text-blue-400"
          to="/issues"
        >
          Issues
        </router-link>
      </div>
      <router-view />
    </div>
  </div>
</template>

<script>
export default {};
</script>
