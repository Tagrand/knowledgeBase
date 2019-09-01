<?php

namespace Tests\Feature\Api;

use App\User;
use App\Source;
use App\Argument;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SourcesArgumentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all_related_arguments() {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();
        $arguments = factory(Argument::class, 2)->create([
            'source_id' => $source->id,
        ]);
        $unlinkedArgument = factory(Argument::class)->create();

        Passport::actingAs($user);
        $response = $this->json('get', "/api/v1/sources/{$source->id}/arguments");

        $response->assertStatus(200);
        $this->assertCount(2, $response->json());
    }
}