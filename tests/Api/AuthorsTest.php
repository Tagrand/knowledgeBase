<?php

namespace Tests\Api;

use App\Author;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;

class AuthorsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_all_authors() {
       $user = factory(User::class)->create();
       factory(Author::class, 2)->create();

       Passport::actingAs($user);
       $response = $this->json('GET', '/api/v1/authors');

       $response->assertStatus(200);
       $this->assertCount(2, $response->json());
    }
}