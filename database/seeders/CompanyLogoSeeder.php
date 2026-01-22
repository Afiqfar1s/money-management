<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CompanyLogoSeeder extends Seeder
{
	public function run(): void
	{
		// NOTE: We store the logo files under the public disk and persist the path
		// into companies.logo_path so it can be exported as SQL later.
		$logos = [
			'MICROCORP' => [
				'filename' => 'microcorp-logo.png',
				'source' => database_path('seeders/logos/microcorp-logo.png'),
			],
			'MNHR' => [
				'filename' => 'mnhr-logo.png',
				'source' => database_path('seeders/logos/mnhr-logo.png'),
			],
		];

		foreach ($logos as $companyCode => $logo) {
			$company = Company::query()->where('code', $companyCode)->first();
			if (!$company) {
				$this->command?->warn("Company with code {$companyCode} not found. Skipping logo seed.");
				continue;
			}

			$relativePath = 'company-logos/' . $logo['filename'];

			if (!file_exists($logo['source'])) {
				$this->command?->warn("Logo file not found at {$logo['source']}. Skipping {$companyCode}.");
				continue;
			}

			// Idempotent write: only write if missing
			// Force overwrite to update logo
			Storage::disk('public')->put($relativePath, file_get_contents($logo['source']));

			$company->update(['logo_path' => $relativePath]);
		}

		$this->command?->info('✅ Seeded company logos for Microcorp and MNHR');
	}
}

