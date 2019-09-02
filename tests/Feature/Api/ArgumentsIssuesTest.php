<?php

namespace Tests\Feature\Api;

use App\User;
use App\Issue;
use App\Argument;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArgumentsIssuesTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_all_related_issues()
    {
        $user = factory(User::class)->create();
        $issue = factory(Issue::class)->create();
        $issue2 = factory(Issue::class)->create();
        $unlinkedIssue = factory(Issue::class)->create();
        $argument = factory(Argument::class)->create();
        $argument->issues()->attach([$issue->id, $issue2->id]);

        Passport::actingAs($user);
        $response = $this->json('GET', "/api/v1/arguments/{$argument->id}/issues");

        $response->assertStatus(200);
        $this->assertCount(2, $response->json());
        $this->assertEquals($issue->id, $response->json()[0]['id']);
    }

    public function test_user_can_see_all_related_issues_even_when_it_has_none()
    {
        $user = factory(User::class)->create();
        $unlinkedIssue = factory(Issue::class)->create();
        $argument = factory(Argument::class)->create();

        Passport::actingAs($user);
        $response = $this->json('GET', "/api/v1/arguments/{$argument->id}/issues");

        $response->assertStatus(200);
        $this->assertCount(0, $response->json());
    }

    public function test_guest_cannot_see_all_related_issues()
    {
        $issue = factory(Issue::class)->create();
        $issue2 = factory(Issue::class)->create();
        $argument = factory(Argument::class)->create();
        $argument->issues()->attach([$issue->id, $issue2->id]);

        $response = $this->json('GET', "/api/v1/argument/{$argument->id}/issues");

        $response->assertStatus(401);
    }

    public function test_the_argument_must_exist_to_see_issues()
    {
        $issue = factory(Issue::class)->create();
        $issue2 = factory(Issue::class)->create();

        $response = $this->json('GET', "/api/v1/argument/1234/issues");

        $response->assertStatus(404);
    }
}