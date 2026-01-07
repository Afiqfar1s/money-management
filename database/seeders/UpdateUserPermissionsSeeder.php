<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateUserPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update all regular users (non-admin) to have default permissions
        $regularUsers = User::where('role', 'user')
            ->whereNull('permissions')
            ->get();

        foreach ($regularUsers as $user) {
            $user->update([
                'permissions' => User::getDefaultPermissions()
            ]);
        }

        $this->command->info('Updated ' . $regularUsers->count() . ' user(s) with default permissions.');
    }
}
