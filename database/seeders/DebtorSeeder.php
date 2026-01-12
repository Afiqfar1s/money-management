<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Debtor;
use App\Models\Payment;
use App\Models\BalanceAdjustment;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class DebtorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data
        $this->command->info('Clearing existing debtor data...');
        
        // Check if using SQLite
        $driver = DB::connection()->getDriverName();
        
        if ($driver !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } else {
            DB::statement('PRAGMA foreign_keys = OFF;');
        }
        
        Payment::truncate();
        BalanceAdjustment::truncate();
        Debtor::truncate();
        
        if ($driver !== 'sqlite') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } else {
            DB::statement('PRAGMA foreign_keys = ON;');
        }

        // Get all users (assuming you have users already)
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->error('No users found! Please create users first.');
            return;
        }

        // Use test user as the owner for all debtors so they can edit/delete
        $testUser = User::where('email', 'test@example.com')->first();
        if (!$testUser) {
            $this->command->warn('Test user not found, using random users as owners.');
            $testUser = null;
        }

        // Get MNHR and Microcorp companies (should exist from TestUserCompanySeeder)
        $this->command->info('Loading MNHR and Microcorp companies...');

        $mnhr = Company::where('code', 'MNHR')->first();
        $microcorp = Company::where('code', 'MICROCORP')->first();

        if (!$mnhr || !$microcorp) {
            $this->command->error('MNHR or Microcorp companies not found! Run TestUserCompanySeeder first.');
            return;
        }

        // Ensure all users have access to both companies
        $mnhr->users()->syncWithoutDetaching($users->pluck('id')->all());
        $microcorp->users()->syncWithoutDetaching($users->pluck('id')->all());

        // Realistic company names (40+ for each company)
        $companyDebtorNames = [
            'Tech Solutions Sdn Bhd', 'Global Trading Co.', 'Metro Construction',
            'Green Energy Systems', 'Digital Marketing Hub', 'Premium Foods Supply',
            'Smart Logistics Sdn Bhd', 'Creative Design Studio', 'Healthcare Solutions',
            'Automotive Parts Malaysia', 'Fashion Boutique Enterprise', 'Property Development Corp',
            'Electronics Wholesale', 'Furniture Kingdom Sdn Bhd', 'Security Services Malaysia',
            'Manufacturing Industries Ltd', 'Retail Solutions Sdn Bhd', 'Food & Beverage Corp',
            'Construction Materials Supply', 'IT Consulting Services', 'Medical Equipment Supply',
            'Transportation Services', 'Event Management Co.', 'Printing & Publishing House',
            'Advertising Agency Sdn Bhd', 'Engineering Consultancy', 'Telecommunications Provider',
            'Insurance Brokers Ltd', 'Real Estate Agency', 'Hotel & Resort Management',
            'Travel & Tourism Sdn Bhd', 'Education Services Provider', 'Legal Consultancy Firm',
            'Accounting & Audit Services', 'Human Resources Solutions', 'Cleaning Services Co.',
            'Catering Services Sdn Bhd', 'Beauty & Wellness Center', 'Fitness & Gym Management',
            'Photography Studio', 'Interior Design Consultancy', 'Landscape Services',
            'Pest Control Services', 'Plumbing Solutions Sdn Bhd', 'Electrical Contractors',
            'Air Conditioning Services', 'Car Rental Agency', 'Courier & Delivery Services',
            'Warehouse & Storage Solutions', 'Import Export Trading'
        ];

        // Realistic individual names (40+ for each company)
        $individualDebtorData = [
            ['name' => 'Ahmad bin Abdullah', 'ic' => '850615-14-5231', 'staff' => 'STF-2024-001'],
            ['name' => 'Siti Nurhaliza binti Hassan', 'ic' => '920823-10-4567', 'staff' => 'STF-2024-002'],
            ['name' => 'Kumar a/l Selvam', 'ic' => '880505-08-3421', 'staff' => 'STF-2024-003'],
            ['name' => 'Lee Wei Ming', 'ic' => '910312-01-8765', 'staff' => 'STF-2024-004'],
            ['name' => 'Fatimah binti Ibrahim', 'ic' => '870920-05-2341', 'staff' => 'STF-2024-005'],
            ['name' => 'Rajesh Kumar', 'ic' => '860728-03-5678', 'staff' => 'STF-2024-006'],
            ['name' => 'Tan Mei Ling', 'ic' => '930415-07-9012', 'staff' => 'STF-2024-007'],
            ['name' => 'Muhammad Faiz bin Ismail', 'ic' => '890102-11-3456', 'staff' => 'STF-2024-008'],
            ['name' => 'Wong Siew Lan', 'ic' => '940806-12-6789', 'staff' => 'STF-2024-009'],
            ['name' => 'Ravi a/l Chandran', 'ic' => '880220-06-4321', 'staff' => 'STF-2024-010'],
            ['name' => 'Nurul Aina binti Ramli', 'ic' => '920918-09-8901', 'staff' => 'STF-2024-011'],
            ['name' => 'Lim Chong Wei', 'ic' => '870503-02-2345', 'staff' => 'STF-2024-012'],
            ['name' => 'Azizah binti Rahman', 'ic' => '910725-14-6543', 'staff' => 'STF-2024-013'],
            ['name' => 'David Tan', 'ic' => '860814-01-7890', 'staff' => 'STF-2024-014'],
            ['name' => 'Kavitha a/p Subramaniam', 'ic' => '930601-08-1234', 'staff' => 'STF-2024-015'],
            ['name' => 'Hassan bin Mahmood', 'ic' => '850320-11-6789', 'staff' => 'STF-2024-016'],
            ['name' => 'Lina binti Rashid', 'ic' => '920615-08-3456', 'staff' => 'STF-2024-017'],
            ['name' => 'Chong Ah Kow', 'ic' => '870910-05-1234', 'staff' => 'STF-2024-018'],
            ['name' => 'Priya a/p Krishnan', 'ic' => '900425-07-8901', 'staff' => 'STF-2024-019'],
            ['name' => 'Abdullah bin Omar', 'ic' => '880718-14-5432', 'staff' => 'STF-2024-020'],
            ['name' => 'Sarah binti Yusof', 'ic' => '930212-10-9876', 'staff' => 'STF-2024-021'],
            ['name' => 'Muthu a/l Raman', 'ic' => '860508-06-2345', 'staff' => 'STF-2024-022'],
            ['name' => 'Chen Li Hua', 'ic' => '910825-03-6789', 'staff' => 'STF-2024-023'],
            ['name' => 'Zainab binti Ahmad', 'ic' => '890405-12-4321', 'staff' => 'STF-2024-024'],
            ['name' => 'Raj Kumar Singh', 'ic' => '870620-09-8765', 'staff' => 'STF-2024-025'],
            ['name' => 'Aishah binti Ismail', 'ic' => '940115-11-2345', 'staff' => 'STF-2024-026'],
            ['name' => 'Lim Seng Huat', 'ic' => '880730-01-6789', 'staff' => 'STF-2024-027'],
            ['name' => 'Rosnah binti Daud', 'ic' => '920518-05-9012', 'staff' => 'STF-2024-028'],
            ['name' => 'Vijay a/l Suresh', 'ic' => '860925-14-3456', 'staff' => 'STF-2024-029'],
            ['name' => 'Emily Wong', 'ic' => '910308-08-7890', 'staff' => 'STF-2024-030'],
            ['name' => 'Hafiz bin Rahman', 'ic' => '890422-02-1234', 'staff' => 'STF-2024-031'],
            ['name' => 'Anita a/p Rao', 'ic' => '930710-07-5678', 'staff' => 'STF-2024-032'],
            ['name' => 'Ng Wei Jie', 'ic' => '870815-12-9012', 'staff' => 'STF-2024-033'],
            ['name' => 'Norizan binti Hashim', 'ic' => '920105-06-3456', 'staff' => 'STF-2024-034'],
            ['name' => 'Suresh a/l Kumar', 'ic' => '880628-10-7890', 'staff' => 'STF-2024-035'],
            ['name' => 'Kok Mei Fong', 'ic' => '940920-04-2345', 'staff' => 'STF-2024-036'],
            ['name' => 'Mohd Rizal bin Ali', 'ic' => '860310-11-6789', 'staff' => 'STF-2024-037'],
            ['name' => 'Kavita a/p Nair', 'ic' => '910525-09-1234', 'staff' => 'STF-2024-038'],
            ['name' => 'Tan Ah Meng', 'ic' => '890815-03-5678', 'staff' => 'STF-2024-039'],
            ['name' => 'Mariam binti Saad', 'ic' => '930402-14-9012', 'staff' => 'STF-2024-040'],
            ['name' => 'Gopal a/l Samy', 'ic' => '870720-01-3456', 'staff' => 'STF-2024-041'],
            ['name' => 'Liew Pei San', 'ic' => '920618-05-7890', 'staff' => 'STF-2024-042'],
            ['name' => 'Azlan bin Yahya', 'ic' => '880905-08-2345', 'staff' => 'STF-2024-043'],
            ['name' => 'Shalini a/p Raj', 'ic' => '940128-12-6789', 'staff' => 'STF-2024-044'],
            ['name' => 'Chong Yew Keong', 'ic' => '860415-07-1234', 'staff' => 'STF-2024-045'],
            ['name' => 'Haslina binti Mansor', 'ic' => '910822-10-5678', 'staff' => 'STF-2024-046'],
            ['name' => 'Ranjit a/l Singh', 'ic' => '890510-14-9012', 'staff' => 'STF-2024-047'],
            ['name' => 'Chua Siew Mei', 'ic' => '930705-04-3456', 'staff' => 'STF-2024-048'],
            ['name' => 'Shahrul bin Azmi', 'ic' => '871230-11-7890', 'staff' => 'STF-2024-049'],
            ['name' => 'Uma a/p Devi', 'ic' => '920418-06-2345', 'staff' => 'STF-2024-050'],
        ];

        $descriptions = [
            'Outstanding payment for services rendered',
            'Pending payment for goods supplied',
            'Monthly service charges outstanding',
            'Loan repayment in progress',
            'Equipment purchase balance',
            'Consultancy fees pending',
            'Rental payment outstanding',
            'Project advance payment',
            'Product delivery charges',
            'Maintenance service fees',
        ];

        $this->command->info('Creating 80+ debtors for MNHR and Microcorp...');

        // Create 40 company debtors for MNHR
        $this->command->info('Creating 40 company debtors for MNHR...');
        for ($i = 0; $i < 40; $i++) {
            $user = $testUser ?? $users->random();
            $startingOutstanding = rand(5000, 50000);
            $totalPaid = rand(1000, max(1000, $startingOutstanding - 500));
            $currentOutstanding = $startingOutstanding - $totalPaid;

            $debtor = Debtor::create([
                'user_id' => $user->id,
                'company_id' => $mnhr->id,
                'debtor_type' => 'company',
                'name' => $companyDebtorNames[$i],
                'phone_number' => '0' . rand(10, 19) . '-' . rand(1000000, 9999999),
                'description' => $descriptions[array_rand($descriptions)],
                'starting_outstanding' => $startingOutstanding,
                'outstanding' => $currentOutstanding,
                'ssm_number' => 'SSM-' . rand(100000, 999999),
                'office_phone' => '03-' . rand(1000, 9999) . ' ' . rand(1000, 9999),
                'company_address' => rand(1, 999) . ', Jalan ' . ['Ampang', 'Bukit Bintang', 'Sultan Ismail', 'Raja Chulan', 'Tun Razak', 'Damansara'][array_rand(['Ampang', 'Bukit Bintang', 'Sultan Ismail', 'Raja Chulan', 'Tun Razak', 'Damansara'])],
            ]);

            // Create random payments (2-5 payments per debtor)
            $numPayments = rand(2, 5);
            $remainingAmount = $totalPaid;
            
            for ($j = 0; $j < $numPayments && $remainingAmount > 0; $j++) {
                $paymentAmount = $j === $numPayments - 1 
                    ? $remainingAmount 
                    : rand(500, min($remainingAmount - 100, 10000));
                
                Payment::create([
                    'debtor_id' => $debtor->id,
                    'amount' => $paymentAmount,
                    'paid_at' => now()->subDays(rand(1, 180)),
                    'voucher_no' => 'MNHR-VCH-' . date('Y') . '-' . str_pad($debtor->id * 100 + $j, 5, '0', STR_PAD_LEFT),
                    'note' => 'Payment received for ' . $companyDebtorNames[$i],
                ]);
                
                $remainingAmount -= $paymentAmount;
            }

            // Sometimes add balance adjustments (30% chance)
            if (rand(0, 100) > 70) {
                $adjustmentAmount = rand(100, 2000);
                BalanceAdjustment::create([
                    'debtor_id' => $debtor->id,
                    'amount' => rand(0, 1) ? $adjustmentAmount : -$adjustmentAmount,
                    'adjusted_at' => now()->subDays(rand(1, 90)),
                    'note' => rand(0, 1) ? 'Late payment penalty added' : 'Discount applied',
                ]);
            }
        }

        // Create 40 individual debtors for MNHR
        $this->command->info('Creating 40 individual debtors for MNHR...');
        for ($i = 0; $i < 40; $i++) {
            $user = $testUser ?? $users->random();
            $individual = $individualDebtorData[$i];
            $startingOutstanding = rand(1000, 15000);
            $totalPaid = rand(500, max(500, $startingOutstanding - 100));
            $currentOutstanding = $startingOutstanding - $totalPaid;

            $debtor = Debtor::create([
                'user_id' => $user->id,
                'company_id' => $mnhr->id,
                'debtor_type' => 'individual',
                'name' => $individual['name'],
                'ic_number' => $individual['ic'],
                'staff_number' => $individual['staff'],
                'phone_number' => '0' . rand(10, 19) . '-' . rand(1000000, 9999999),
                'description' => $descriptions[array_rand($descriptions)],
                'starting_outstanding' => $startingOutstanding,
                'outstanding' => $currentOutstanding,
                'position' => ['Manager', 'Senior Executive', 'Executive', 'Assistant', 'Supervisor', 'Officer', 'Coordinator'][array_rand(['Manager', 'Senior Executive', 'Executive', 'Assistant', 'Supervisor', 'Officer', 'Coordinator'])],
                'start_working_date' => now()->subYears(rand(1, 10))->subDays(rand(1, 365)),
                'address' => 'No. ' . rand(1, 999) . ', Taman ' . ['Melawati', 'Tun Dr Ismail', 'Keramat', 'Gombak', 'Setapak', 'Cheras'][array_rand(['Melawati', 'Tun Dr Ismail', 'Keramat', 'Gombak', 'Setapak', 'Cheras'])],
            ]);

            // Create random payments (1-4 payments per individual)
            $numPayments = rand(1, 4);
            $remainingAmount = $totalPaid;
            
            for ($j = 0; $j < $numPayments && $remainingAmount > 0; $j++) {
                $paymentAmount = $j === $numPayments - 1 
                    ? $remainingAmount 
                    : rand(200, min($remainingAmount - 50, 3000));
                
                Payment::create([
                    'debtor_id' => $debtor->id,
                    'amount' => $paymentAmount,
                    'paid_at' => now()->subDays(rand(1, 150)),
                    'voucher_no' => 'MNHR-VCH-' . date('Y') . '-' . str_pad($debtor->id * 100 + $j, 5, '0', STR_PAD_LEFT),
                    'note' => 'Payment received from ' . $individual['name'],
                ]);
                
                $remainingAmount -= $paymentAmount;
            }

            // Sometimes add balance adjustments (25% chance)
            if (rand(0, 100) > 75) {
                $adjustmentAmount = rand(50, 1000);
                BalanceAdjustment::create([
                    'debtor_id' => $debtor->id,
                    'amount' => rand(0, 1) ? $adjustmentAmount : -$adjustmentAmount,
                    'adjusted_at' => now()->subDays(rand(1, 60)),
                    'note' => rand(0, 1) ? 'Administrative fee' : 'Early payment discount',
                ]);
            }
        }

        // Create 40 company debtors for Microcorp
        $this->command->info('Creating 40 company debtors for Microcorp...');
        for ($i = 0; $i < 40; $i++) {
            $user = $testUser ?? $users->random();
            $startingOutstanding = rand(5000, 50000);
            $totalPaid = rand(1000, max(1000, $startingOutstanding - 500));
            $currentOutstanding = $startingOutstanding - $totalPaid;

            $debtor = Debtor::create([
                'user_id' => $user->id,
                'company_id' => $microcorp->id,
                'debtor_type' => 'company',
                'name' => $companyDebtorNames[$i],
                'phone_number' => '0' . rand(10, 19) . '-' . rand(1000000, 9999999),
                'description' => $descriptions[array_rand($descriptions)],
                'starting_outstanding' => $startingOutstanding,
                'outstanding' => $currentOutstanding,
                'ssm_number' => 'SSM-' . rand(100000, 999999),
                'office_phone' => '03-' . rand(1000, 9999) . ' ' . rand(1000, 9999),
                'company_address' => rand(1, 999) . ', Jalan ' . ['Ampang', 'Bukit Bintang', 'Sultan Ismail', 'Raja Chulan', 'Tun Razak', 'Damansara'][array_rand(['Ampang', 'Bukit Bintang', 'Sultan Ismail', 'Raja Chulan', 'Tun Razak', 'Damansara'])],
            ]);

            // Create random payments (2-5 payments per debtor)
            $numPayments = rand(2, 5);
            $remainingAmount = $totalPaid;
            
            for ($j = 0; $j < $numPayments && $remainingAmount > 0; $j++) {
                $paymentAmount = $j === $numPayments - 1 
                    ? $remainingAmount 
                    : rand(500, min($remainingAmount - 100, 10000));
                
                Payment::create([
                    'debtor_id' => $debtor->id,
                    'amount' => $paymentAmount,
                    'paid_at' => now()->subDays(rand(1, 180)),
                    'voucher_no' => 'MCORP-VCH-' . date('Y') . '-' . str_pad($debtor->id * 100 + $j, 5, '0', STR_PAD_LEFT),
                    'note' => 'Payment received for ' . $companyDebtorNames[$i],
                ]);
                
                $remainingAmount -= $paymentAmount;
            }

            // Sometimes add balance adjustments (30% chance)
            if (rand(0, 100) > 70) {
                $adjustmentAmount = rand(100, 2000);
                BalanceAdjustment::create([
                    'debtor_id' => $debtor->id,
                    'amount' => rand(0, 1) ? $adjustmentAmount : -$adjustmentAmount,
                    'adjusted_at' => now()->subDays(rand(1, 90)),
                    'note' => rand(0, 1) ? 'Late payment penalty added' : 'Discount applied',
                ]);
            }
        }

        // Create 40 individual debtors for Microcorp
        $this->command->info('Creating 40 individual debtors for Microcorp...');
        for ($i = 0; $i < 40; $i++) {
            $user = $testUser ?? $users->random();
            $individual = $individualDebtorData[$i];
            $startingOutstanding = rand(1000, 15000);
            $totalPaid = rand(500, max(500, $startingOutstanding - 100));
            $currentOutstanding = $startingOutstanding - $totalPaid;

            $debtor = Debtor::create([
                'user_id' => $user->id,
                'company_id' => $microcorp->id,
                'debtor_type' => 'individual',
                'name' => $individual['name'],
                'ic_number' => $individual['ic'],
                'staff_number' => $individual['staff'],
                'phone_number' => '0' . rand(10, 19) . '-' . rand(1000000, 9999999),
                'description' => $descriptions[array_rand($descriptions)],
                'starting_outstanding' => $startingOutstanding,
                'outstanding' => $currentOutstanding,
                'position' => ['Manager', 'Senior Executive', 'Executive', 'Assistant', 'Supervisor', 'Officer', 'Coordinator'][array_rand(['Manager', 'Senior Executive', 'Executive', 'Assistant', 'Supervisor', 'Officer', 'Coordinator'])],
                'start_working_date' => now()->subYears(rand(1, 10))->subDays(rand(1, 365)),
                'address' => 'No. ' . rand(1, 999) . ', Taman ' . ['Melawati', 'Tun Dr Ismail', 'Keramat', 'Gombak', 'Setapak', 'Cheras'][array_rand(['Melawati', 'Tun Dr Ismail', 'Keramat', 'Gombak', 'Setapak', 'Cheras'])],
            ]);

            // Create random payments (1-4 payments per individual)
            $numPayments = rand(1, 4);
            $remainingAmount = $totalPaid;
            
            for ($j = 0; $j < $numPayments && $remainingAmount > 0; $j++) {
                $paymentAmount = $j === $numPayments - 1 
                    ? $remainingAmount 
                    : rand(200, min($remainingAmount - 50, 3000));
                
                Payment::create([
                    'debtor_id' => $debtor->id,
                    'amount' => $paymentAmount,
                    'paid_at' => now()->subDays(rand(1, 150)),
                    'voucher_no' => 'MCORP-VCH-' . date('Y') . '-' . str_pad($debtor->id * 100 + $j, 5, '0', STR_PAD_LEFT),
                    'note' => 'Payment received from ' . $individual['name'],
                ]);
                
                $remainingAmount -= $paymentAmount;
            }

            // Sometimes add balance adjustments (25% chance)
            if (rand(0, 100) > 75) {
                $adjustmentAmount = rand(50, 1000);
                BalanceAdjustment::create([
                    'debtor_id' => $debtor->id,
                    'amount' => rand(0, 1) ? $adjustmentAmount : -$adjustmentAmount,
                    'adjusted_at' => now()->subDays(rand(1, 60)),
                    'note' => rand(0, 1) ? 'Administrative fee' : 'Early payment discount',
                ]);
            }
        }

        $this->command->info('✅ Successfully created 160 debtors (80 per company) with payments and adjustments!');
        $this->command->info('MNHR: 40 companies + 40 individuals = 80 debtors');
        $this->command->info('Microcorp: 40 companies + 40 individuals = 80 debtors');
    }
}
