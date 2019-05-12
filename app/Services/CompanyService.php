<?php

namespace App\Services;

use App\File;
use App\Company;
use App\Services\FileService;
use App\Services\LocationService;

class CompanyService
{
    public function __construct(
        FileService $fileService,
        LocationService $locationService
        )
    {
        $this->fileService = $fileService;
        $this->locationService = $locationService;
    }

    public function list()
    {
        return Company::with('user')->whereHas('user', function ($query) {
            return $query->where('status', 1);
        })->get();
    }

    public function store($data)
    {
        $company = Company::create($data);

        $location = $this->locationService->store([
            'lat' => $data['location']['lat'],
            'lng' => $data['location']['lng'],
        ]);

        $location->plain_text_address = $this->locationService
                                             ->getFormattedAddressString($location);

        $location->save();

        $company->location()->associate($location);
        $company->save();

        if (isset($data['profile_photo']) && $this->fileService->isBase64Image($data['profile_photo'])) {
            File::upload_file($company, $data['profile_photo'], 'profile_photo');

        }

        return $company;
    }

    public function getDetailedCompany($company_id)
    {
        return Company::with([
            'user',
            'location',
        ])->find($company_id);
    }

    public function update(Company $company, $data)
    {
        $company->update($data);

        $company->location()->dissociate();

        $location = $this->locationService->store([
            'lat' => $data['location']['lat'],
            'lng' => $data['location']['lng'],
        ]);

        $location->plain_text_address = $this->locationService->getFormattedAddressString($location);

        $location->save();

        $company->location()->associate($location);
        $company->save();

        if (isset($data['profile_photo']) && $this->fileService->isBase64Image($data['profile_photo'])) {
            if ($company->profilePhoto()) {
                File::delete_file($company->profilePhoto()->path . $company->profilePhoto()->name);
            }

            File::upload_file($company, $data['profile_photo'], 'profile_photo');
        }

        return $company;
    }

    public function delete(Company $company)
    {
        $user = $company->user;

        $user->roles()->detach();
        $user->assignRole(config('constants.roles.client'));

        if ($company->profilePhoto()) {
            File::delete_file($company->profilePhoto()->path . $company->profilePhoto()->name);
        }

        $company->delete();
    }
}
