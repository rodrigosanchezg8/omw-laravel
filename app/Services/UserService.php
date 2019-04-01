<?php

namespace App\Services;

use App\File;
use App\User;
use App\Services\CompanyService;

class UserService
{
    public function __construct(CompanyService $companyService)
    {
        $this->companyService = $companyService;
    }

    public function list($role)
    {
        return User::with([
            'roles',
            'company',
            'city',
        ])->roleFilter($role)
            ->get();
    }

    public function store($data)
    {
        $user = User::create($data);

        $user->assignRole(config('constants.roles.client'));

        if (isset($data['profile_photo'])) {

            File::upload_profile_photo($user, $data['profile_photo']);

        }

        return $user;
    }

    public function getDetailedUser($user_id)
    {
        return User::with([
            'roles',
            'company',
            'city' => function ($query) {
                return $query->with('state');
            },
        ])->find($user_id);
    }

    public function update(User $user, $data)
    {
        $user->update($data);

        if (isset($data['profile_photo'])) {
            if ($user->profilePhoto()) {
                File::delete_profile_photo($user->profilePhoto()->path);
            }

            File::upload_profile_photo($user, $data['profile_photo']);
        }
    }

    public function delete(User $user)
    {
        if ($user->hasRole('company')) {

            $this->companyService->delete($user->company);

        }

        if ($user->profilePhoto()) {
            File::delete_profile_photo($user->profilePhoto()->path);
        }

        $user->delete();
    }
}
