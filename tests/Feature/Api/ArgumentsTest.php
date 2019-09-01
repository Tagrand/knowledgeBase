<?php

namespace Tests\Feature\Api;

use App\User;
use App\Source;
use App\Argument;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArgumentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_see_all_arguments()
    {
        $user = factory(User::class)->create();
        factory(Argument::class, 2)->create();

        Passport::actingAs($user);
        $response = $this->json('GET', '/api/v1/arguments');

        $response->assertStatus(200);
        $this->assertCount(2, $response->json());
    }

    public function test_a_guest_cannot_see_arguments()
    {
        factory(Argument::class, 2)->create();

        $response = $this->json('GET', '/api/v1/arguments');

        $response->assertStatus(401);
    }

    public function test_a_user_can_make_an_argument()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/arguments', [
            'reason' => 'This is a good one',
            'source_id' => $source->id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('arguments', [
            'reason' => 'This is a good one',
            'source_id' => $source->id,
        ]);
    }

    public function test_sources_are_optional()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/arguments', [
            'reason' => 'This is a good one',
            // 'source_id' => $source->id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('arguments', [
            'reason' => 'This is a good one',
        ]);
    }

    public function test_an_argument_must_have_a_reason()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/arguments', [
            // 'reason' => 'This is a good one',
            'source_id' => $source->id,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('reason');
    }

    public function test_reasons_must_be_strings()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/arguments', [
            'reason' => 121212,
            'source_id' => $source->id,
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
        $response = $this->json('POST', '/api/v1/arguments', [
            'reason' => 'THIS IS TRUE',
            'source_id' => $source->id,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('reason');
    }

    public function test_sources_must_exist() {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/arguments', [
            'reason' => 'THIS IS TRUE',
            'source_id' => 12121,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('source_id');
    }
}
