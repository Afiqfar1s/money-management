<?php

namespace App\View\Composers;

use App\Models\Company;
use Illuminate\View\View;

class CompanyComposer
{
    public function compose(View $view): void
    {
        $companyId = (int) session('current_company_id');
        $currentCompany = null;

        if ($companyId > 0) {
            $currentCompany = Company::query()
                ->select(['id', 'name', 'logo_path'])
                ->find($companyId);
        }

        $view->with('currentCompany', $currentCompany);
    }
}
