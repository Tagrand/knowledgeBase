## Requests

1. Two ways to access Requests in controller 
    (Request $request)
    or the request('key') helper. 
    The first way is better

2. $validated = $request->validate(['key' => 'rule'])
3. Useful rules to remeber 'required|string|integer'

## Routing

1. The Api.php will automatcally add api to the url
2. Route::prefix()->group() <- to add a url base to all urls in this group
3. Route::middleware('')->group() <- add middleware to everything in this group
4. Route::resource('') <- not sure, need to look up 

## Controllers

1. php artisan make:controller --resource <- will create all your traditional crud enpoints
2. \_\_invoke if you want this to always run on request

## Testing

### Apis
1. $this->json('GET', 'Path') <- to hit api end points
2. $response->json() to access json data
3. $response->assertJsonValidationErrors

## database

1. For join tables you want the singular (in alphabetical order). 'fact_issue'
2. For foriegn keys  
    $table->foreign('issue_id')
                  ->references('id')
                  ->on('issues')
                  ->onDelete('cascade');
3. the foriegn key is table_column_foreign
4. Don't forget to dropForeign when you go down
5.
