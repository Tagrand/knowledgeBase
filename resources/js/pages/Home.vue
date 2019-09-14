<template>
  <div>
    <h1 class="font-headline text-center text-5xl font-bold mb-4">
      News Center
    </h1>
    <div class="md:flex w-full justify-between">
      <news-block-vue
        name="First Check"
        :news-list="firstCheck"
        class="bg-grey md:w-2/5"
      />
    </div>
    <div class="bg-grey w-2/5">
      <h2>Up to date</h2>
    </div>
    <div>
      <div>Balance</div>
      <div>Humourous</div>
    </div>
  </div>
</template>

<script>
import NewsBlockVue from '../components/NewsBlock.vue';

export default {
  components: { NewsBlockVue },
  data() {
    return {
      firstCheck: [
        {
          name: 'Wren Lewis',
          url: 'https://mainlymacro.blogspot.com',
        },
        {
          name: 'George Monbiot',
          url: 'https://www.monbiot.com/articles',
        },
        {
          name: 'Labour list',
          url: 'https://labourlist.org/',
        },
        {
          name: 'Matthew Norman',
          url: 'https://www.independent.co.uk/author/matthew-norman',
        },
        {
          url: 'https://www.theguardian.com/uk/commentisfree',
          name: 'Guardian opinion',
        },
      ],
      depth: [
        {
          url: 'https://fivethirtyeight.com',
          name: '538',
        },
        {
          url: 'https://fullfact.org/blog/',
          name: 'Full fact',
        },
        {
          url: 'https://forum.effectivealtruism.org/allPosts',
          name: 'Ea forum',
        },
      ],
      humorous: [
        {

          url: 'https://www.theguardian.com/profile/davidmitchell',
          name: 'David Mitchel',

        },
        {
          url: 'https://www.theguardian.com/profile/victoriacoren',
          name: 'Victoria Coren Mitchel',
        },
      ],
      balance: [
        {
          name: 'Conservative Home',
          url: 'https://www.conservativehome.com/category/highlights',
        },
      ],
    };
  },
};
</script>
