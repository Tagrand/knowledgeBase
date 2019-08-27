<?php

namespace Tests\Feature\Api;

use App\User;
use App\Source;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SourcesTest extends TestCase
{
    use RefreshDatabase;

    /**  test */
    public function test_a_user_can_get_all_the_sources()
    {
        $user = factory(User::class)->create();
        factory(Source::class, 2)->create();

        Passport::actingAs($user);
        $response = $this->get('/api/v1/sources');

        $response->assertStatus(200);
        $this->assertCount(2, $response->json());
    }

    public function test_a_guest_cannot_get_all_the_sources()
    {
        factory(Source::class, 2)->create();

        $response = $this->get('/api/v1/sources');

        $response->assertStatus(302);
    }

    public function test_a_user_can_add_a_new_source()
    {
        $user = factory(User::class)->create();
        factory(Source::class, 2)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/sources', [
            'name' => 'the guardian',
            'summary' => 'the guardian',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('sources', [
            'name' => 'the guardian',
            'summary' => 'the guardian',
        ]);
    }

    public function test_summary_is_optional()
    {
        $user = factory(User::class)->create();
        factory(Source::class, 2)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/sources', [
            'name' => 'the guardian',
            // 'summary' => 'the guardian',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('sources', [
            'name' => 'the guardian',
        ]);
    }

    public function test_a_source_must_have_a_name()
    {
        $user = factory(User::class)->create();
        factory(Source::class, 2)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/sources', [
            // 'name' => 'the guardian',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_names_must_be_strings()
    {
        $user = factory(User::class)->create();
        factory(Source::class, 2)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/sources', [
            'name' => 123,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_summaries_must_be_strings()
    {
        $user = factory(User::class)->create();
        factory(Source::class, 2)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/sources', [
            'name' => '121212',
            'summary' => 121212,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('summary');
    }

    public function test_sources_cannot_be_made_by_guests()
    {
        factory(Source::class, 2)->create();

        $response = $this->json('POST', '/api/v1/sources', [
            'name' => 'this shouldn\'t work',
        ]);

        $response->assertStatus(401);
    }
}
