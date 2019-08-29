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

    public function test_users_can_get_all_facts() {
        $user= factory(User::class)->create();
        $facts = factory(Fact::class, 4)->create();

        Passport::actingAs($user);
        $response = $this->json('get', '/api/v1/facts');

        $response->assertStatus(200);
        $this->assertCount(4, $response->json());
    }
}