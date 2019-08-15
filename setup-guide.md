1 - laravel new project
2 - link to github 
3 - make the psql database and give a user access. Give that user a password.
4 - setup DB
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

