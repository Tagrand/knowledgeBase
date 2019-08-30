<?php

namespace Tests\Feature\Api;

use App\User;
use App\Author;
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

    public function test_a_user_can_add_an_author_to_a_source()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();
        $author = factory(Author::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/sources/{$source->id}/authors/{$author->id}");

        $response->assertStatus(204);
        $this->assertDatabaseHas('author_source', [
            'author_id' => $author->id,
            'source_id' => $source->id,
        ]);
    }

    public function test_the_author_must_exist()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/sources/{$source->id}/authors/1231");

        $response->assertStatus(404);
    }

    public function test_the_source_must_exist()
    {
        $user = factory(User::class)->create();
        $author = factory(Author::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/sources/12342/authors/{$author->id}");

        $response->assertStatus(404);
    }

    public function test_guests_cannot_link_authors_and_sources()
    {
        $source = factory(Source::class)->create();
        $author = factory(Author::class)->create();

        $response = $this->json('POST', "/api/v1/sources/{$source->id}/authors/{$author->id}");

        $response->assertStatus(401);
    }

    public function test_a_user_can_detach_an_author_from_a_source()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();
        $author = factory(Author::class)->create();
        $source->authors()->attach($author);

        Passport::actingAs($user);
        $response = $this->json('DELETE', "/api/v1/sources/{$source->id}/authors/{$author->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('author_source', [
            'author_id' => $author->id,
            'source_id' => $source->id,
        ]);
    }
}
