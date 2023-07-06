<?php

use App\Models\Company;

if(!function_exists('getCompanyIdBySlug')){
    function getCompanyIdBySlug($slug) {
        $company_id = Company::where('slug', $slug)->pluck('id')->first();
        return $company_id;
    }
}