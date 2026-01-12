<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestUserCompanySeeder extends Seeder
{
    /**
     * Create Microcorp + MNHR and attach them to test@example.com.
     */
    public function run(): void
    {
        $user = User::where('email', 'test@example.com')->first();

        if (! $user) {
            $this->command?->warn('Test user (test@example.com) not found. Skipping TestUserCompanySeeder.');
            return;
        }

        $companies = [
            ['name' => 'Microcorp', 'code' => 'MICROCORP'],
            ['name' => 'MNHR', 'code' => 'MNHR'],
        ];

        foreach ($companies as $c) {
            $company = Company::firstOrCreate(
                ['code' => $c['code']],
                ['name' => $c['name']]
            );

            $company->users()->syncWithoutDetaching([$user->id]);
        }

        $this->command?->info('✅ Assigned Microcorp and MNHR to test@example.com');
    }
}
