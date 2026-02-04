<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Debtor;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestDebtorsSeeder extends Seeder
{
    /**
     * Seed test debtors for Microcorp and MNHR.
     */
    public function run(): void
    {
        $microcorp = Company::where('code', 'MICROCORP')->first();
        $mnhr = Company::where('code', 'MNHR')->first();
        $testUser = User::where('email', 'test@example.com')->first();

        if (!$microcorp || !$mnhr) {
            $this->command->warn('Companies not found. Please run TestUserCompanySeeder first.');
            return;
        }

        if (!$testUser) {
            $this->command->warn('Test user not found.');
            return;
        }

        // Microcorp Debtors - Mix of individuals and companies
        $microcorpDebtors = [
            ['name' => 'Ahmad bin Hassan', 'type' => 'individual', 'outstanding' => 5000.00, 'description' => 'Hardware supplies order #1234'],
            ['name' => 'Siti Nurul Aisyah', 'type' => 'individual', 'outstanding' => 2500.50, 'description' => 'Office furniture purchase'],
            ['name' => 'Tech Solutions Sdn Bhd', 'type' => 'company', 'outstanding' => 15000.00, 'description' => 'IT equipment and services'],
            ['name' => 'Mohamed Ali Trading', 'type' => 'company', 'outstanding' => 8750.25, 'description' => 'Bulk stationery order'],
            ['name' => 'Tan Wei Ming', 'type' => 'individual', 'outstanding' => 1200.00, 'description' => 'Laptop repair and upgrade'],
            ['name' => 'Global Resources Bhd', 'type' => 'company', 'outstanding' => 25000.00, 'description' => 'Annual maintenance contract'],
            ['name' => 'Fatimah binti Abdullah', 'type' => 'individual', 'outstanding' => 3500.75, 'description' => 'Graphic design services'],
            ['name' => 'Lee Construction Co', 'type' => 'company', 'outstanding' => 45000.00, 'description' => 'Office renovation project'],
            ['name' => 'Kumar a/l Rajan', 'type' => 'individual', 'outstanding' => 0.00, 'description' => 'Paid in full - Software license'],
            ['name' => 'Smart Logistics Sdn Bhd', 'type' => 'company', 'outstanding' => 12500.50, 'description' => 'Transportation and delivery services'],
        ];

        // MNHR Debtors - Different set of data
        $mnhrDebtors = [
            ['name' => 'Rajesh Kumar', 'type' => 'individual', 'outstanding' => 3200.00, 'description' => 'Consulting fees for Q1'],
            ['name' => 'Wong Mei Ling', 'type' => 'individual', 'outstanding' => 0.00, 'description' => 'Fully settled - Training services'],
            ['name' => 'Prime Industries Bhd', 'type' => 'company', 'outstanding' => 18900.00, 'description' => 'Manufacturing equipment order'],
            ['name' => 'Noor Hidayah', 'type' => 'individual', 'outstanding' => 4500.00, 'description' => 'Marketing campaign materials'],
            ['name' => 'Eastern Holdings Sdn Bhd', 'type' => 'company', 'outstanding' => 32000.00, 'description' => 'Supply chain management system'],
            ['name' => 'David Lim', 'type' => 'individual', 'outstanding' => 1850.25, 'description' => 'Photography and video services'],
            ['name' => 'Metro Trading Co', 'type' => 'company', 'outstanding' => 9500.00, 'description' => 'Wholesale products purchase'],
            ['name' => 'Sarah binti Ismail', 'type' => 'individual', 'outstanding' => 2750.50, 'description' => 'Legal consultation services'],
            ['name' => 'Pacific Engineering Bhd', 'type' => 'company', 'outstanding' => 55000.00, 'description' => 'Engineering design and build'],
            ['name' => 'Chong Kar Wai', 'type' => 'individual', 'outstanding' => 6200.00, 'description' => 'Website development and hosting'],
        ];

        // Create Microcorp debtors
        foreach ($microcorpDebtors as $debtor) {
            Debtor::create([
                'company_id' => $microcorp->id,
                'user_id' => $testUser->id,
                'debtor_type' => $debtor['type'],
                'name' => $debtor['name'],
                'description' => $debtor['description'],
                'outstanding' => $debtor['outstanding'],
            ]);
        }

        // Create MNHR debtors
        foreach ($mnhrDebtors as $debtor) {
            Debtor::create([
                'company_id' => $mnhr->id,
                'user_id' => $testUser->id,
                'debtor_type' => $debtor['type'],
                'name' => $debtor['name'],
                'description' => $debtor['description'],
                'outstanding' => $debtor['outstanding'],
            ]);
        }

        $this->command->info('✅ Seeded 10 debtors for Microcorp');
        $this->command->info('✅ Seeded 10 debtors for MNHR');
        $this->command->info('Total: 20 test debtors created');
    }
}
