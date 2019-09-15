<?php

namespace Tests\Feature\Api;

use App\Fact;
use App\User;
use App\Argument;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FactsArgumentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_all_related_arguments_to_issue()
    {
        $user = factory(User::class)->create();
        $unattachedArgument = factory(Argument::class)->create();
        $argument1 = factory(Argument::class)->create();
        $argument2 = factory(Argument::class)->create();
        $fact = factory(Fact::class)->create();
        $fact->arguments()->attach([$argument1->id, $argument2->id]);

        Passport::actingAs($user);
        $response = $this->json('GET', "/api/v1/facts/{$fact->id}/arguments");

        $response->assertStatus(200);
        $this->assertCount(2, $response->json());
        $this->assertEquals($argument1->id, $response->json()[0]['id']);
    }
}
