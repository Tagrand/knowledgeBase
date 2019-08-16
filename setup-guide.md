--- Set up laravel with login --- 
1. - laravel new project
2. - link to github 
3. - make the psql database and give a user access. Give that user a password.
4. - setup DB
    - if on heroku use psql
       database.php - set the default to psql (and do it in your local env)
       
    $url = parse_url(getenv('DATABASE_URL'));

   'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', $url['host']),
            'port' => env('DB_PORT', $url['port']),
            'database' => env('DB_DATABASE', $url['database']),
            'username' => env('DB_USERNAME', $url['username']),
            'password' => env('DB_PASSWORD', $url['pass']),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],
5. If you want to add login on the quick 
    php artisan make:auth
    php artisan migrate

6.  php artisan make:model -m (The m will also make a migration)
    remeber to add $guarded = [] property and casts property 
    php artisan make:migration create_whatever_table (useful types - string, text, integer, bool)

---- Setup passport ---- 

1. composer require laravel/passport

2. php artisan migrate

3. php artisan passport:install

4. add HasApiToken trait to the User

5. The AuthServiceProvider should look like this
protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }

6. In auth.php  set api driver 'driver' => 'passport',

7. If you're using normal sessions you can do something pretty simple to consume the api. Laravel will let you consume your own api. 
   You just need to add the \Laravel\Passport\Http\Middleware\CreateFreshApiToken::class to the web middleware in Kernel.php

   this will add a laravel_token cooke to outgoing responses. Which contains a JWT that passport will authenticate API requests from. 

   laravel api has a /user endpoint by default. You can use this to test out if its working.

---- Add vue in ---- 
1. npm install vue vue-router vuex --save

2. Rename welcome.blade.php to app.blade.php.

3. add in a div to attach vue to and a link to the js code
         <div id="app"></div>

        <script src="{{ asset('js/app.js') }}"></script>

	if you're using authentification replace welcome with this.
          @extends('layouts.app')

          @section('content')
            <div id="app"></div>
            <script src="{{ asset('js/app.js') }}"></script> 
          @endsection

4. in the head add a meta tag - this is to get the csrf token for axios to use. 
        <meta name="csrf-token" content="{{ csrf_token() }}">

5. (If using VueRouter) add Route::get('/{any}', function() {return view('app')} )->where('any', '.*'); to your web file.
   -- this is so you don't end up changing page each time. You can just put it at the bottom and the other routes can remain
   -- don't forget to change welcome in the home path to app too

   if you're using authentification too 
	  Auth::routes();
	  Route::get('/{any}', 'HomeController@index')->where('any', '.*');
   Then change the HomeController to return App. 

   Also, in the App.blade.php file, in layouts' you'll see an id='app'. Remove the id or vue will just replace all that.

   Then delete the home.blade.php file


6. Change your app.js file to this and remove the bootstrap file (as it contains a load of not that useful stuff - I've moved axios to this file).

    import Vue from 'vue';
    import Vuex from 'vuex';
    import App from '@/js/views/App';
    import Routes from '@/js/routes.js';

    Vue.use(Vuex);

    window._ = require('lodash');
    window.axios = require('axios');

    window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    let token = document.head.querySelector('meta[name="csrf-token"]');

    if (token) {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
    } else {
        console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
    }

    const app = new Vue({
        el: '#app',
        router: Routes,
        render: h => h(App),
    });

7. Make folds layouts, pages, stores, views, and delete the example component in the components folder (in js).

8. Make Home.vue for now.
   
9. In views, add App.vue. With this
<template>
<div>
   <p> Hello </p>
   <router-view></router-view>
</div>   
</template>

<script>
export default {};
</script>

10. In the main directory add this in a new routes.js file

import Vue from 'vue';
import VueRouter from 'vue-router';
import Home from '@/js/components/Home';

Vue.use(VueRouter);

const router = new VueRouter({
    mode: 'history',
    routes: [
      {
          path: '/',
          name: 'home',
          component: Home,
      },
    ],
});

export default router;

11. Then in the webpack file add this 
mix.webpackConfig({
    resolve: {
        extensions: ['.js', '.vue'],
        alias: {
            '@': __dirname + '/resources',
        },
    },
})

12. Run npm run watch. And it should all...work...

---- Mailtrap -----

.env
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=INSERT
MAIL_PASSWORD=INSERT
MAIL_ENCRYPTION=tls
