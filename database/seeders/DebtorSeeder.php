<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Debtor;
use Illuminate\Database\Seeder;

class DebtorSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('email', 'test@example.com')->first();
        
        if (!$user) {
            echo "User not found!\n";
            return;
        }

        $names = [
            'John Smith', 'Sarah Johnson', 'Michael Brown', 'Emma Davis', 'James Wilson',
            'Olivia Martinez', 'Robert Anderson', 'Sophia Taylor', 'William Thomas', 'Ava Garcia',
            'David Miller', 'Isabella Rodriguez', 'Richard Moore', 'Mia Martin', 'Joseph Lee',
            'Charlotte White', 'Thomas Harris', 'Amelia Clark', 'Charles Lewis', 'Harper Walker',
            'Daniel Hall', 'Evelyn Young', 'Matthew King', 'Abigail Wright', 'Anthony Lopez',
            'Emily Hill', 'Mark Scott', 'Elizabeth Green', 'Donald Adams', 'Sofia Nelson'
        ];

        $descriptions = [
            'Regular customer', 'Wholesale buyer', 'Monthly supplier', 'One-time purchase',
            'Long-term client', 'New customer', 'VIP client', 'Retail partner', 
            'Corporate account', 'Small business owner'
        ];

        foreach ($names as $name) {
            $outstanding = rand(0, 10) > 2 ? rand(100, 5000) : 0;
            
            Debtor::create([
                'user_id' => $user->id,
                'name' => $name,
                'description' => $descriptions[array_rand($descriptions)],
                'starting_outstanding' => $outstanding,
                'outstanding' => $outstanding
            ]);
        }

        echo "30 debtors created successfully!\n";
    }
}
