<?php

namespace Tests\Feature\Api;

use App\Fact;
use App\User;
use App\Source;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SourcesFactsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_see_all_related_facts()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();
        $fact = factory(Fact::class, 2)->create([
            'source_id' => $source->id,
        ]);

        Passport::actingAs($user);
        $response = $this->json('GET', "/api/v1/sources/{$source->id}/facts");

        $response->assertStatus(200);
        $this->assertCount(2, $response->json());
    }

    public function test_a_guest_cannot_see_unrelated_facts()
    {
        $source = factory(Source::class)->create();
        $fact = factory(Fact::class, 2)->create([
            'source_id' => $source->id,
        ]);

        $response = $this->json('GET', "/api/v1/sources/{$source->id}/facts");

        $response->assertStatus(401);
    }

    public function test_user_can_make_facts()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/sources/{$source->id}/facts", [
            'claim' => 'This is a claim',
            'summary' => 'its a good claim',
            'image' => '1111.com',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('facts', [
             'source_id' => $source->id,
             'claim' => 'This is a claim',
             'summary' => 'its a good claim',
             'image' => '1111.com',
         ]);
    }

    public function test_summary_and_image_are_optional()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/sources/{$source->id}/facts", [
            'claim' => 'This is a claim',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('facts', [
             'source_id' => $source->id,
             'claim' => 'This is a claim',
             'summary' => null,
             'image' => null,
         ]);
    }

    public function test_guests_cannot_make_facts()
    {
        $source = factory(Source::class)->create();

        $response = $this->json('POST', "/api/v1/sources/{$source->id}/facts", [
            'claim' => 'This is a claim',
        ]);

        $response->assertStatus(401);
    }

    public function test_the_fact_must_have_a_source()
    {
        $user = factory(User::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/sources/1234/facts", [
            'claim' => 'This is a claim',
        ]);

        $response->assertStatus(404);
    }

    public function test_a_fact_must_have_a_claim()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/sources/{$source->id}/facts", [
            // 'claim' => 'This is a claim',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('claim');
    }

    public function test_a_fact_must_be_a_string()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/sources/{$source->id}/facts", [
            'claim' => 1234123,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('claim');
    }

    public function test_a_summary_must_be_a_string()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/sources/{$source->id}/facts", [
            'claim' => 'asdasd',
            'summary' => 121212,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('summary');
    }

    public function test_a_image_must_be_a_string()
    {
        $user = factory(User::class)->create();
        $source = factory(Source::class)->create();

        Passport::actingAs($user);
        $response = $this->json('POST', "/api/v1/sources/{$source->id}/facts", [
            'claim' => 'asdasd',
            'image' => 121212,
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('image');
    }
}
