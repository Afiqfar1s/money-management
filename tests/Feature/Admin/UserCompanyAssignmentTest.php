<?php

namespace Tests\Feature\Admin;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserCompanyAssignmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_assign_companies_when_creating_a_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $companyA = Company::query()->create(['name' => 'Company A']);
        $companyB = Company::query()->create(['name' => 'Company B']);

        $response = $this->actingAs($admin)->post(route('users.store', absolute: false), [
            'name' => 'New User',
            'email' => 'new-user@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'user',
            'permissions' => User::getDefaultPermissions(),
            'company_ids' => [$companyA->id, $companyB->id],
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('users.index', absolute: false));

        $created = User::query()->where('email', 'new-user@example.com')->firstOrFail();

        $this->assertCount(2, $created->companies);
        $this->assertTrue($created->companies->pluck('id')->contains($companyA->id));
        $this->assertTrue($created->companies->pluck('id')->contains($companyB->id));

        $this->assertDatabaseHas('company_user', [
            'user_id' => $created->id,
            'company_id' => $companyA->id,
        ]);

        $this->assertDatabaseHas('company_user', [
            'user_id' => $created->id,
            'company_id' => $companyB->id,
        ]);
    }

    public function test_admin_can_update_company_assignments_for_a_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $targetUser = User::factory()->create([
            'role' => 'user',
            'permissions' => User::getDefaultPermissions(),
        ]);

        $companyA = Company::query()->create(['name' => 'Company A']);
        $companyB = Company::query()->create(['name' => 'Company B']);

        // Start with Company A assigned
        $targetUser->companies()->sync([$companyA->id]);
        $this->assertTrue($targetUser->fresh()->companies->pluck('id')->contains($companyA->id));

        // Update: remove A, add B
        $response = $this->actingAs($admin)->put(route('users.update', $targetUser, absolute: false), [
            'name' => $targetUser->name,
            'email' => $targetUser->email,
            'role' => 'user',
            'permissions' => $targetUser->permissions,
            'company_ids' => [$companyB->id],
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('users.index', absolute: false));

        $targetUser->refresh();

        $this->assertFalse($targetUser->companies->pluck('id')->contains($companyA->id));
        $this->assertTrue($targetUser->companies->pluck('id')->contains($companyB->id));

        $this->assertDatabaseMissing('company_user', [
            'user_id' => $targetUser->id,
            'company_id' => $companyA->id,
        ]);

        $this->assertDatabaseHas('company_user', [
            'user_id' => $targetUser->id,
            'company_id' => $companyB->id,
        ]);
    }
}
