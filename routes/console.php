<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('data:clear-debtors {--force : Skip confirmation prompt}', function () {
    return $this->call(\App\Console\Commands\ClearDebtorData::class, [
        '--force' => (bool) $this->option('force'),
    ]);
})->purpose('Delete all debtor-related data (payments, balance adjustments, and debtors).');
