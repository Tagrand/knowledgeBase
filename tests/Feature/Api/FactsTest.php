<?php

namespace Tests\Feature\Api;

use App\Fact;
use App\User;
use App\Source;
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
            'summary' => 'is true',
            'image' => 'true',
        ]);

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/facts/{$fact->id}", [
            'claim' => 'it is not',
            'summary' => 'not',
            'image' => 'thinking',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('facts', [
            'id' => $fact->id,
            'claim' => 'it is not',
            'summary' => 'not',
            'image' => 'thinking',
        ]);
    }

    public function test_users_can_edit_a_facts_source()
    {
        $user= factory(User::class)->create();
        $fact = factory(Fact::class)->create([
            'claim' => 'this is true',
        ]);
        $source = factory(Source::class)->create();

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/facts/{$fact->id}", [
            'source_id' => $source->id,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('facts', [
            'id' => $fact->id,
            'claim' => 'this is true',
            'source_id' => $source->id,
        ]);
    }

    public function test_summary_can_be_null()
    {
        $user= factory(User::class)->create();
        $fact = factory(Fact::class)->create([
            'claim' => 'yes is true',
            'summary' => 'trying',
        ]);

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/facts/{$fact->id}", [
            'summary' => '',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('facts', [
            'id' => $fact->id,
            'claim' => 'yes is true',
            'summary' => null,
        ]);
    }

    public function test_images_can_be_null()
    {
        $user= factory(User::class)->create();
        $fact = factory(Fact::class)->create([
            'claim' => 'yes is true',
            'image' => 'trying',
        ]);

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/facts/{$fact->id}", [
            'image' => '',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('facts', [
            'id' => $fact->id,
            'claim' => 'yes is true',
            'image' => null,
        ]);
    }

    public function test_facts_must_exist_to_be_edited()
    {
        $user= factory(User::class)->create();

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/facts/165151", [
            'claim' => 'it is not',
        ]);

        $response->assertStatus(404);
    }

    public function test_guests_cannot_edit_facts()
    {
        $fact = factory(Fact::class)->create([
            'claim' => 'this is true',
        ]);

        $response = $this->json('PATCH', "/api/v1/facts/{$fact->id}", [
            'claim' => 'it is not',
        ]);

        $response->assertStatus(401);
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

    public function test_summaries_must_be_strings()
    {
        $user= factory(User::class)->create();
        $fact = factory(Fact::class)->create([
            'summary' => 'kajsdkjaskd',
        ]);

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/facts/{$fact->id}", [
            'summary' => 125151,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('summary');
    }

    public function test_images_must_be_strings()
    {
        $user= factory(User::class)->create();
        $fact = factory(Fact::class)->create([
            'image' => 'kajsdkjaskd',
        ]);

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/facts/{$fact->id}", [
            'image' => 125151,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image');
    }

    public function test_claims_cannot_be_duplicated()
    {
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

    public function test_source_must_exist_to_be_added()
    {
        $user= factory(User::class)->create();
        $fact = factory(Fact::class)->create([
            'claim' => 'this is false',
        ]);

        Passport::actingAs($user);
        $response = $this->json('PATCH', "/api/v1/facts/{$fact->id}", [
            'source_id' => 12612,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('source_id');
    }
}
