<?php

namespace Tests\Feature\Api;

use App\User;
use App\Author;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthorsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_all_authors()
    {
        $user = factory(User::class)->create();
        factory(Author::class, 2)->create();

        Passport::actingAs($user);
        $response = $this->json('GET', '/api/v1/authors');

        $response->assertStatus(200);
        $this->assertCount(2, $response->json());
    }

    public function test_guest_cannot_see_authors()
    {
        factory(Author::class, 2)->create();

        $response = $this->json('GET', '/api/v1/authors');

        $response->assertStatus(401);
    }

    public function test_user_can_create_an_author()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/authors', [
           'first_name' => 'Ed',
           'last_name' => 'Milliband',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('authors', [
           'first_name' => 'Ed',
           'last_name' => 'Milliband',
        ]);
    }

    public function test_authors_must_have_first_names()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/authors', [
        //    'first_name' => 'Ed',
           'last_name' => 'Milliband',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('first_name');
    }

    public function test_first_names_must_be_strings()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/authors', [
           'first_name' => 1234,
           'last_name' => 'Milliband',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('first_name');
    }

    public function test_authors_must_have_last_names()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/authors', [
           'first_name' => 'Ed',
        //    'last_name' => 'Milliband',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('last_name');
    }

    public function test_last_names_must_be_strings()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/authors', [
           'first_name' => 'Ed',
           'last_name' => 12434,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('last_name');
    }

    public function test_last_and_first_name_combinations_must_be_unique()
    {
        $user = factory(User::class)->create();
        factory(Author::class)->create([
            'first_name' => 'Diane',
            'last_name' => 'Abbot',
        ]);

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/authors', [
            'first_name' => 'Diane',
            'last_name' => 'Abbot',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('last_name');
    }
}
