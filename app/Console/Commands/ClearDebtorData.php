<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearDebtorData extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'data:clear-debtors {--force : Skip confirmation prompt}';

    /**
     * The console command description.
     */
    protected $description = 'Delete all debtor-related data (payments, balance adjustments, and debtors).';

    public function handle(): int
    {
        if (!$this->option('force')) {
            if (!$this->confirm('This will DELETE ALL debtors, payments, and balance adjustments. Continue?')) {
                $this->info('Aborted.');
                return self::SUCCESS;
            }
        }

        $driver = DB::connection()->getDriverName();

        $this->info('Clearing debtor-related data...');

        DB::transaction(function () use ($driver) {
            if ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = OFF;');
            } else {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            }

            // Order matters due to FKs.
            DB::table('payments')->truncate();
            DB::table('balance_adjustments')->truncate();
            DB::table('debtors')->truncate();

            if ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = ON;');
            } else {
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            }
        });

        $counts = [
            'payments' => (int) DB::table('payments')->count(),
            'balance_adjustments' => (int) DB::table('balance_adjustments')->count(),
            'debtors' => (int) DB::table('debtors')->count(),
        ];

        $this->line('Remaining rows:');
        $this->line('- payments: ' . $counts['payments']);
        $this->line('- balance_adjustments: ' . $counts['balance_adjustments']);
        $this->line('- debtors: ' . $counts['debtors']);

        $this->info('Done.');

        return self::SUCCESS;
    }
}
