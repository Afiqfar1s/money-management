<?php

use App\Models\Company;
use App\Models\Debtor;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Make idempotent and safe for SQLite.
        if (!Schema::hasTable('debtors') || !Schema::hasTable('companies') || !Schema::hasTable('company_user')) {
            return;
        }

        // Create a fallback company if none exists.
        $fallback = Company::query()->first();
        if (!$fallback) {
            $fallback = Company::query()->create([
                'name' => 'Default Company',
                'code' => 'DEFAULT',
            ]);
        }

        // Make sure all existing users can access fallback (useful for local environments).
        $userIds = User::query()->pluck('id')->all();
        if (!empty($userIds)) {
            $fallback->users()->syncWithoutDetaching($userIds);
        }

        // Backfill debtors that don't have company_id.
        $debtors = Debtor::query()->whereNull('company_id')->get();
        foreach ($debtors as $debtor) {
            $debtor->company_id = $fallback->id;
            $debtor->save();
        }
    }

    public function down(): void
    {
        // No-op: we don't want to null-out tenant data.
    }
};
