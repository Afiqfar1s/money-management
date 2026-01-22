<?php

namespace Tests\Feature\Admin;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CompanyLogoTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_company_logo(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'role' => 'admin',
            'permissions' => [],
        ]);

        $this->actingAs($admin);

        $response = $this->post(route('companies.store'), [
            'name' => 'LogoCo',
            'code' => 'LOGO',
            'logo' => UploadedFile::fake()->image('logo.png', 200, 200),
        ]);

        $response->assertRedirect(route('companies.index'));

        $company = Company::where('name', 'LogoCo')->firstOrFail();
        $this->assertNotNull($company->logo_path);

        Storage::disk('public')->assertExists($company->logo_path);
    }
}
