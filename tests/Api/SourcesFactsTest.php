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
        $this->withoutExceptionHandling();
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
}