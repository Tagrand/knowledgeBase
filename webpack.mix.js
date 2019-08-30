const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

mix.webpackConfig({
  resolve: {
    extensions: ['.js', '.vue'],
    alias: {
      '@': `${__dirname}/resources`,
    },
  },
});

mix.js('resources/js/app.js', 'public/js');
mix.sass('resources/sass/app.scss', 'public/css')
  .options({
    processCssUrls: false,
    postCss: [tailwindcss('./tailwind.config.js')],
  });
