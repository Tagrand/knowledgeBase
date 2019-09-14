<template>
  <div>
    <h2 class="font-headline text-center text-2xl font-bold my-2">
      {{ title }}
    </h2>
    <div
      v-for="(item, index) in collection"
      :key="`${item.id}${item[infoName]}`"
      class="text-center"
    >
      <span>{{ index + 1 }}.</span>
      <span>
        {{ item[infoName] }}
      </span>
      <span v-show="extraInfo">
        {{ item[extraInfo] }}
      </span>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    title: {
      required: true,
      type: String,
    },
    collection: {
      required: true,
      type: Array,
    },
    infoName: {
      required: true,
      type: String,
    },
    extraInfo: {
      default: null,
      type: String,
    },
  },
};
</script>
