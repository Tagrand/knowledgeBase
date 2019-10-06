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

    public function test_can_get_all_related_arguments()
    {
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

    public function test_guests_cannot_see_arguments()
    {
        $source = factory(Source::class)->create();
        $arguments = factory(Argument::class, 2)->create([
            'source_id' => $source->id,
        ]);
        $unlinkedArgument = factory(Argument::class)->create();

        $response = $this->json('get', "/api/v1/sources/{$source->id}/arguments");

        $response->assertStatus(401);
    }

    public function test_a_user_can_make_an_argument()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/sources/{$source->id}/arguments", [
            'reason' => 'This is a good one',
            'summary' => 'valid point',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('arguments', [
            'reason' => 'This is a good one',
            'summary' => 'valid point',
            'source_id' => $source->id,
        ]);
    }

    public function test_an_argument_must_have_a_reason()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/sources/{$source->id}/arguments", [
            // 'reason' => 'This is a good one',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('reason');
    }

    public function test_reasons_must_be_strings()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/sources/{$source->id}/arguments", [
            'reason' => 121212,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('reason');
    }

    public function test_reasons_must_be_unique()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();
        $argument = factory(Argument::class)->create([
            'reason' => 'THIS IS TRUE',
        ]);

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/sources/{$source->id}/arguments", [
            'reason' => 'THIS IS TRUE',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('reason');
    }

    public function test_summaries_must_be_strings()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/sources/{$source->id}/arguments", [
            'reason' => '121212',
            'summary' => 12121212,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('summary');
    }

    public function test_sources_must_exist()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/sources/12121/arguments", [
            'reason' => 'THIS IS TRUE',
            'source_id' => 12121,
        ]);

        $response->assertStatus(404);
    }
}
