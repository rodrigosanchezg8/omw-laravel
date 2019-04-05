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
        $user->city()->associate($data['city_id']);

        $user->assignRole($data['role']['name']);

        if (isset($data['profile_photo']) && FileService::isBase64Image($data['profile_photo'])) {

            File::upload_file($user, $data['profile_photo'], 'profile_photo');

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
        $user->city()->dissociate();
        $user->city()->associate($data['city']['id']);

        if (isset($data['profile_photo']) && FileService::isBase64Image($data['profile_photo'])) {
            if ($user->profilePhoto()) {
                File::delete_file($user->profilePhoto()->path . $user->profilePhoto()->name);
            }

            File::upload_file($user, $data['profile_photo'], 'profile_photo');
        }
    }


    public function delete(User $user)
    {
        if ($user->hasRole('company')) {

            $this->companyService->delete($user->company);

        }

        if ($user->profilePhoto()) {
            File::delete_file($user->profilePhoto()->path);
        }

        $user->delete();
    }
}
