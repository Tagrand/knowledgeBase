<?php

namespace Tests\Api;

use App\Author;
use App\User;
use App\Source;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SourcesAuthorsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_see_all_authors()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();
        $author = factory(Author::class)->create();
        $author2 = factory(Author::class)->create();
        $unlinkedAuthor = factory(Author::class)->create();
        $source->authors()->attach([$author->id, $author2->id]);

        Passport::actingAs($user);
        $response = $this->json('GET', "/api/v1/sources/{$source->id}/authors");

        $response->assertStatus(200);
        $this->assertCount(2, $response->json());
    }

    public function test_a_guest_cannot_see_all_authors()
    {
        $source = factory(Source::class)->create();
        $author = factory(Author::class)->create();
        $author2 = factory(Author::class)->create();
        $source->authors()->attach([$author->id, $author2->id]);

        $response = $this->json('GET', "/api/v1/sources/{$source->id}/authors");

        $response->assertStatus(401);
    }
}
