<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Projects;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;

class TheProjectTest extends TestCase
{
    use RefreshDatabase;
    protected $email;
    protected $password = 'password';
    protected $authCode;

    // Before each test, create a user account
    protected function setUp(): void {
        parent::setUp();
        $this->create_user_account();
    }

    public function create_user_account(): void {
        $this->email = 'user' . rand(0, 10000) . '+' . time() . '@nice.local';

        $response = $this->post('/register', [
            'name' => 'test',
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password,
        ]);

        $response->assertStatus(200);

        $response = $this->post('/login', [
            'email' => $this->email,
            'password' => $this->password,
        ]);

        $this->authCode = $response->json()['token'];
    }
    /** @test */
    public function it_can_create_a_project()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->authCode,
        ])->postJson('/projects', [
            'name' => 'Test Project',
            'description' => 'Test Description',
        ]);

        $response->assertStatus(201);
    }

    /** @test */
    public function it_can_update_a_project()
    {
        $project = Projects::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->authCode,
        ])->putJson("/projects/{$project->id}", [
            'name' => 'Updated Project',
            'description' => 'Updated Description',
        ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_delete_a_project()
    {
        $project = Projects::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->authCode,
        ])->deleteJson("/projects/{$project->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    /** @test */
    public function it_can_get_all_projects()
    {
        $projects = Projects::factory()->count(5)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->authCode,
        ])->getJson('/projects');

        $response->assertStatus(200);
        $response->assertJsonCount(5, 'data');
    }

        /** @test */
    public function it_can_get_a_single_project()
    {
        $project = Projects::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->authCode,
        ])->getJson("/projects/{$project->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => $project->name]);
    }

}