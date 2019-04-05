<?php

namespace App\Services;

use App\Company;
use App\File;

class CompanyService
{

    public function list()
    {
        return Company::with('user')->get();
    }

    public function store($data)
    {
        $company = Company::create($data);

        $user = $company->user;

        $user->roles()->detach();
        $user->assignRole(config('constants.roles.company'));

        if (isset($data['profile_photo'])) {
            File::upload_file($company, $data['profile_photo'], 'profile_photo');
        }

        return $company;
    }

    public function getDetailedCompany($company_id)
    {
        return Company::with([
            'user',
            'city',
        ])->find($company_id);
    }

    public function update(Company $company, $data)
    {
        $company->update($data);

        if (isset($data['profile_photo'])) {
            if ($company->profilePhoto()) {
                File::delete_file($company->profilePhoto()->path);
            }

            File::upload_file($company, $data['profile_photo'], 'profile_photo');
        }
    }

    public function delete(Company $company)
    {
        $user = $company->user;

        $user->roles()->detach();
        $user->assignRole(config('constants.roles.client'));

        if ($company->profilePhoto()) {
            File::delete_file($company->profilePhoto()->path);
        }

        $company->delete();
    }
}
