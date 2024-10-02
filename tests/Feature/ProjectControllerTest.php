<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test unauthenticated users cannot access project routes.
     */
    public function test_guest_cannot_access_project_routes()
    {
        $this->get(route('projects.index'))->assertRedirect('login');
        $this->get(route('projects.create'))->assertRedirect('login');
        $this->post(route('projects.store'))->assertRedirect('login');
        $this->put(route('projects.update', 1))->assertRedirect('login');
        $this->delete(route('projects.destroy', 1))->assertRedirect('login');
    }

    /**
     * Test authenticated users can view project index.
     */
    public function test_authenticated_user_can_view_projects()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('projects.index'));
        $response->assertStatus(200);
    }

    /**
     * Test project creation with valid data.
     */
    public function test_authenticated_user_can_create_project_with_valid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $projectData = [
            'name' => 'Project Alpha',
            'description' => 'This is the description for Project Alpha.',
        ];

        $response = $this->post(route('projects.store'), $projectData);
        $response->assertRedirect(route('projects.index'));

        $this->assertDatabaseHas('projects', $projectData);
    }

    /**
     * Test project creation fails with invalid data.
     */
    public function test_project_creation_fails_with_invalid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('projects.store'), []);
        $response->assertSessionHasErrors(['name', 'description']);
    }

    /**
     * Test authenticated user can delete a project.
     */
    public function test_authenticated_user_can_delete_project()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $project = Project::factory()->create();

        $response = $this->delete(route('projects.destroy', $project->id));
        $response->assertRedirect(route('projects.index'));

        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }
}

