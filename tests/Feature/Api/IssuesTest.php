<?php

namespace Tests\Feature\Api;

use App\User;
use App\Issue;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IssuesTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_get_all_issues()
    {
        $user = factory(User::class)->create();
        factory(Issue::class, 2)->create();

        Passport::actingAs($user);
        $response = $this->json('GET', '/api/v1/issues');

        $response->assertStatus(200);
        $this->assertCount(2, $response->json());
    }

    public function test_a_guest_cannot_get_all_issues()
    {
        factory(Issue::class, 2)->create();

        $response = $this->json('GET', '/api/v1/issues');

        $response->assertStatus(401);
    }

    public function test_a_user_can_make_an_issue()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/issues', [
            'name' => 'Electoral reform',
            'summary' => 'Better election system',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('issues', [
            'name' => 'Electoral reform',
            'summary' => 'Better election system',
        ]);
    }

    public function test_a_guest_cannot_make_an_issue()
    {
        $user = factory(User::class)->create();

        $response = $this->json('POST', '/api/v1/issues', [
            'name' => 'Electoral reform',
            'summary' => 'Better election system',
        ]);

        $response->assertStatus(401);
    }

    public function test_a_issue_must_have_a_name()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/issues', [
            // 'name' => 'Electoral reform',
            'summary' => 'Better election system',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_a_name_must_be_a_string()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/issues', [
            'name' => 124,
            'summary' => 'Better election system',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_summary_is_optional()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/issues', [
            'name' => 'Electoral reform',
            // 'summary' => 'Better election system',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('issues', [
            'name' => 'Electoral reform',
            'summary' => null,
        ]);
    }

    public function test_a_summary_must_be_a_string()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', '/api/v1/issues', [
            'name' => 'this',
            'summary' => 123,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('summary');
    }

    public function test_a_user_can_edit_an_issue()
    {
        $user = factory(User::class)->create();
        $issue = factory(Issue::class)->create([
            'name' => 'thius',
            'summary' => 'trial',
        ]);

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/issues/{$issue->id}", [
            'name' => 'Electoral reform',
            'summary' => 'Better election system',
        ]);

        $response->assertStatus(204);
        $this->assertDatabaseHas('issues', [
            'name' => 'Electoral reform',
            'summary' => 'Better election system',
        ]);
    }

    public function test_the_summary_can_be_null() {
        $user = factory(User::class)->create();
        $issue = factory(Issue::class)->create([
            'name' => 'thius',
            'summary' => 'jahsdjhjashd',
        ]);

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/issues/{$issue->id}", [
            'summary' => '',
        ]);

        $response->assertStatus(204);
        $this->assertDatabaseHas('issues', [
            'id' => $issue->id,
            'name' => 'thius',
            'summary' => null,
        ]);
    }

    public function test_a_guest_cannot_edit_an_issue()
    {
        $issue = factory(Issue::class)->create();

        $response = $this->json('PATCH', "/api/v1/issues/{$issue->id}", [
            'name' => 'Electoral reform',
            'summary' => 'Better election system',
        ]);

        $response->assertStatus(401);
    }

    public function test_the_issue_must_exist()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $response = $this->json('PATCH', '/api/v1/issues/1231', [
            'name' => 'Electoral reform',
            'summary' => 'Better election system',
        ]);

        $response->assertStatus(404);
    }

    public function test_the_name_cannot_be_null() {
        $user = factory(User::class)->create();
        $issue = factory(Issue::class)->create([
            'name' => 'thius',
            'summary' => 'trial',
        ]);

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/issues/{$issue->id}", [
            'name' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('name');
    }

    public function test_the_name_must_be_a_string()
    {
        $user = factory(User::class)->create();
        $issue = factory(Issue::class)->create([
            'name' => 'thius',
            'summary' => 'trial',
        ]);

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/issues/{$issue->id}", [
            'name' => 'Electoral reform',
        ]);

        $response->assertStatus(204);
        $this->assertDatabaseHas('issues', [
            'name' => 'Electoral reform',
            'summary' => 'trial',
        ]);
    }

}
