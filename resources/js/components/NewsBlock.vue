<template>
  <div>
    <h2 class="font-headline text-center text-2xl font-bold my-2">
      {{ name }}
    </h2>
    <div
      v-for="(news, index) in newsList"
      :key="news.name"
      class="text-center"
    >
      <span>{{ index + 1 }}.</span>
      <a
        class="hover:text-blue-400"
        :href="news.url"
        target="blank"
        rel="noopener noreferrer"
      >{{ news.name }}</a>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    name: {
      type: String,
      required: true,
    },
    newsList: {
      type: Array,
      required: true,
    },
  },
};
</script>
