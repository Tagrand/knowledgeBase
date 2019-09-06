<?php

namespace Tests\Feature\Api;

use App\User;
use App\Issue;
use App\Argument;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IssuesArgumentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_you_can_see_all_linked_arguments() {
        $user = factory(User::class)->create();
        $argument1 = factory(Argument::class)->create();
        $argument2 = factory(Argument::class)->create();
        $unlinkedArgument = factory(Argument::class)->create();
        $issue = factory(Issue::class)->create();
        $issue->arguments()->attach([$argument1->id, $argument2->id]);

        Passport::actingAs($user);
        $response = $this->json('GET', "/api/v1/issues/{$issue->id}/arguments");

        $response->assertStatus(200);
        $this->assertCount(2, $response->json());
        $this->assertEquals($argument1->id, $response->json()[0]['id']);
    }

    public function test_guests_cannot_see_linked_arguments() {
        $argument1 = factory(Argument::class)->create();
        $argument2 = factory(Argument::class)->create();
        $unlinkedArgument = factory(Argument::class)->create();
        $issue = factory(Issue::class)->create();
        $issue->arguments()->attach([$argument1->id, $argument2->id]);

        $response = $this->json('GET', "/api/v1/issues/{$issue->id}/arguments");

        $response->assertStatus(401);
    }
}