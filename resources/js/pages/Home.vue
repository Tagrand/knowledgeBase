<template>
  <div>
    Hello
    <button @click="test">Go</button>
  </div>
</template>

<script>
export default {
  created() {
    axios
      .get("/api/user")
      .then(result => console.log)
      .catch(result => console.log);
  },

  methods: {
    test() {
      axios
        .get("/api/user")
        .then(result => console.log)
        .catch(result => console.log);
    }
  }
};
</script>