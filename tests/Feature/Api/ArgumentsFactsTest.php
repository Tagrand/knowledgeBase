<?php

namespace Tests\Feature\Api;

use App\Fact;
use App\User;
use App\Argument;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArgumentsFactsTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_join_facts_and_arguments() {
        $fact = factory(Fact::class)->create();
        $argument = factory(Argument::class)->create();
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/arguments/{$argument->id}/facts/{$fact->id}");

        $response->assertStatus(200);
        $this->assertDatabaseHas('argument_fact', [
            'argument_id' => $argument->id,
            'fact_id' => $fact->id,
            'is_supportive' => true,
        ]);
    }

    public function test_can_make_facts_unsupportive_of_arguments() {
        $fact = factory(Fact::class)->create();
        $argument = factory(Argument::class)->create();
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/arguments/{$argument->id}/facts/{$fact->id}", [
            'is_supportive' => false,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('argument_fact', [
            'argument_id' => $argument->id,
            'fact_id' => $fact->id,
            'is_supportive' => false,
        ]);
    }

    public function test_fact_must_exist() {
        $argument = factory(Argument::class)->create();
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/arguments/{$argument->id}/facts/12121", [
            'is_supportive' => false,
        ]);

        $response->assertStatus(404);
    }
}