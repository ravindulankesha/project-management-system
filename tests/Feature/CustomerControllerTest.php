<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CustomerControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test unauthenticated users cannot access the customer routes.
     */
    public function test_guest_cannot_access_customer_routes()
    {
        // Test guest is redirected from each route
        $this->get(route('customers.index'))->assertRedirect('login');
        $this->get(route('customers.create'))->assertRedirect('login');
        $this->post(route('customers.store'))->assertRedirect('login');
        $this->put(route('customers.update', 1))->assertRedirect('login');
        $this->delete(route('customers.destroy', 1))->assertRedirect('login');
    }

    /**
     * Test authenticated users can view customer index.
     */
    public function test_authenticated_user_can_view_customers()
    {
        // Simulate an authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Assert that they can access the customers index
        $response = $this->get(route('customers.index'));
        $response->assertStatus(200);
    }

    /**
     * Test customer creation with validation.
     */
    public function test_authenticated_user_can_create_customer_with_valid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Simulate customer data
        $customerData = [
            'name' => 'John Doe',
            'phone' => '1234567890',
            'email' => 'johndoe@example.com',
            'country' => 'USA',
        ];

        // Post valid data to store route
        $response = $this->post(route('customers.store'), $customerData);
        $response->assertRedirect(route('customers.index'));

        // Assert the customer was created in the database
        $this->assertDatabaseHas('customers', $customerData);
    }

    /**
     * Test customer creation fails with invalid data.
     */
    public function test_customer_creation_fails_with_invalid_data()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Post invalid data (missing fields)
        $response = $this->post(route('customers.store'), []);
        $response->assertSessionHasErrors(['name', 'phone', 'email', 'country']);
    }

    /**
     * Test customer can be deleted.
     */
    public function test_authenticated_user_can_delete_customer()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a customer
        $customer = Customer::factory()->create();

        // Delete the customer
        $response = $this->delete(route('customers.destroy', $customer->id));
        $response->assertRedirect(route('customers.index'));

        // Assert the customer is deleted from the database
        $this->assertDatabaseMissing('customers', ['id' => $customer->id]);
    }
}
