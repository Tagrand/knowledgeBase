<?php

namespace Tests\Feature\Api;

use App\Fact;
use App\User;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FactsTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_get_all_facts()
    {
        $user= factory(User::class)->create();
        $facts = factory(Fact::class, 4)->create();

        Passport::actingAs($user);
        $response = $this->json('get', '/api/v1/facts');

        $response->assertStatus(200);
        $this->assertCount(4, $response->json());
    }

    public function test_guests_cannot_get_facts()
    {
        $facts = factory(Fact::class, 4)->create();

        $response = $this->json('get', '/api/v1/facts');

        $response->assertStatus(401);
    }

    public function test_users_can_edit_a_fact()
    {
        $user= factory(User::class)->create();
        $fact = factory(Fact::class)->create([
            'claim' => 'this is true',
        ]);

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/facts/{$fact->id}", [
            'claim' => 'it is not',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('facts', [
            'id' => $fact->id,
            'claim' => 'it is not',
        ]);
    }

    public function test_claims_cannot_be_null()
    {
        $user= factory(User::class)->create();
        $fact = factory(Fact::class)->create([
            'claim' => 'kajsdkjaskd',
        ]);

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/facts/{$fact->id}", [
            'claim' => '',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('claim');
    }

    public function test_claims_must_be_strings()
    {
        $user= factory(User::class)->create();
        $fact = factory(Fact::class)->create([
            'claim' => 'kajsdkjaskd',
        ]);

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/facts/{$fact->id}", [
            'claim' => 125151,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('claim');
    }

    public function test_claims_cannot_be_duplicated() {
        $user= factory(User::class)->create();
        factory(Fact::class)->create([
            'claim' => 'this is true',
        ]);
        $fact = factory(Fact::class)->create([
            'claim' => 'this is false',
        ]);

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/facts/{$fact->id}", [
            'claim' => 'this is true',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('claim');
    }
}
