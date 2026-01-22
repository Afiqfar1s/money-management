<?php

namespace Tests\Feature\Authorization;

use App\Models\Company;
use App\Models\Debtor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionEnforcementTest extends TestCase
{
    use RefreshDatabase;

    private function createCompanyWithUser(array $userOverrides = []): array
    {
        $company = Company::create(['name' => 'TestCo']);

        $user = User::factory()->create(array_merge([
            'role' => 'user',
            'permissions' => [],
        ], $userOverrides));

        $user->companies()->attach($company->id);

        return [$company, $user];
    }

    public function test_user_without_view_permissions_cannot_access_debtor_index(): void
    {
        [$company, $user] = $this->createCompanyWithUser();

        $this->actingAs($user)
            ->withSession(['current_company_id' => $company->id])
            ->get(route('debtors.index'))
            ->assertForbidden();
    }

    public function test_user_with_view_debtors_permission_sees_all_company_debtors(): void
    {
        [$company, $user] = $this->createCompanyWithUser([
            'permissions' => ['view_debtors'],
        ]);

        $otherUser = User::factory()->create([
            'role' => 'user',
            'permissions' => ['view_debtors'],
        ]);
        $otherUser->companies()->attach($company->id);

        Debtor::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'debtor_type' => 'individual',
            'name' => 'Mine',
            'description' => null,
            'starting_outstanding' => 100,
            'outstanding' => 100,
        ]);

        Debtor::create([
            'user_id' => $otherUser->id,
            'company_id' => $company->id,
            'debtor_type' => 'individual',
            'name' => 'Other User',
            'description' => null,
            'starting_outstanding' => 100,
            'outstanding' => 100,
        ]);

        $response = $this->actingAs($user)
            ->withSession(['current_company_id' => $company->id])
            ->get(route('debtors.index'));

        $response->assertOk();
        $response->assertSee('Mine');
        $response->assertSee('Other User'); // Now users see ALL company debtors
    }

    public function test_user_without_create_permission_cannot_create_debtor(): void
    {
        [$company, $user] = $this->createCompanyWithUser([
            'permissions' => ['view_own_debtors'],
        ]);

        $payload = [
            'debtor_type' => 'individual',
            'name' => 'New Debtor',
            'description' => null,
            'starting_outstanding' => 123.45,
            'staff_number' => 'S-1',
            'ic_number' => 'IC-1',
            'phone_number' => '0123456789',
            'start_working_date' => now()->toDateString(),
        ];

        $this->actingAs($user)
            ->withSession(['current_company_id' => $company->id])
            ->post(route('debtors.store'), $payload)
            ->assertForbidden();
    }

    public function test_user_without_manage_payments_cannot_store_payment(): void
    {
        [$company, $user] = $this->createCompanyWithUser([
            'permissions' => ['view_own_debtors', 'create_debtors', 'edit_own_debtors'],
        ]);

        $debtor = Debtor::create([
            'user_id' => $user->id,
            'company_id' => $company->id,
            'debtor_type' => 'individual',
            'name' => 'Payer',
            'description' => null,
            'starting_outstanding' => 100,
            'outstanding' => 100,
        ]);

        $this->actingAs($user)
            ->withSession(['current_company_id' => $company->id])
            ->post(route('payments.store', $debtor), [
                'voucher_no' => 'VCH-001',
                'amount' => 10,
                'paid_at' => now()->toDateString(),
            ])
            ->assertForbidden();
    }

    public function test_admin_can_access_debtors_index_without_explicit_permissions(): void
    {
        $company = Company::create(['name' => 'AdminCo']);

        $admin = User::factory()->create([
            'role' => 'admin',
            'permissions' => [],
        ]);

        $admin->companies()->attach($company->id);

        $this->actingAs($admin)
            ->withSession(['current_company_id' => $company->id])
            ->get(route('debtors.index'))
            ->assertOk();
    }
}
