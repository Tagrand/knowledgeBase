<?php

namespace Tests\Api;

use App\User;
use App\Source;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SourcesFactsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_make_facts() {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/sources/{$source->id}/facts", [
            'claim' => 'This is a claim',
        ]);

         $response->assertStatus(204);
         $this->assertDatabaseHas('facts', [
             'source_id' => $source->id, 
             'claim' => 'This is a claim',
         ]);
    }

    public function test_guests_cannot_make_facts() {
        $source = factory(Source::class)->create();

        $response = $this->json('POST', "/api/v1/sources/{$source->id}/facts", [
            'claim' => 'This is a claim',
        ]);

        $response->assertStatus(401);
    }

    public function test_the_fact_must_have_a_source() {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/sources/1234/facts", [
            'claim' => 'This is a claim',
        ]);

         $response->assertStatus(404);
    }
}