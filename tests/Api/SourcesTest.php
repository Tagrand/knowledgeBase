<?php

namespace Tests\Feature;

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

    public function test_a_guest_cannot_get_all_the_sources() {
        factory(Source::class, 2)->create();

        $response = $this->get('/api/v1/sources');

        $response->assertStatus(302);
    }

    public function test_a_user_can_add_a_new_source() {
        $user = factory(User::class)->create();
        factory(Source::class, 2)->create();

        Passport::actingAs($user);
        $response = $this->json('POST','/api/v1/sources', [
            'name' => 'the guardian',
        ]);

        $response->assertStatus(204);
        $this->assertDatabaseHas('sources', [
            'name' => 'the guardian',
        ]);
    }
}
