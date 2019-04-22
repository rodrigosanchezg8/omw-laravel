<?php

namespace App\Services;

use App\File;
use App\User;
use App\Location;
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
            'location'
        ])->roleFilter($role)
            ->get();
    }

    public function store($data)
    {
        $user = User::create($data);
        $location = Location::create([
            'lat' => $data['location']['lat'],
            'lng' => $data['location']['lng'],
            'origin' => 'address'
        ]);
        $user->location()->associate($location);
        $user->save();

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
            'company' => function ($query) {
                return $query->with('location');
            },
            'location'
        ])->find($user_id);
    }

    public function getDetailedClientByEmail($email)
    {
        $user = User::whereEmail($email)->with([
            'roles',
            'company' => function ($query) {
                return $query->with('location');
            },
            'location'
        ])->first();


        if (!$user)
            throw new \Exception("No hay ningún cliente con éste correo", 1);

        return $user;
    }

    public function update(User $user, $data)
    {
        $user->update($data);
        $user->location()->dissociate();
        $location = Location::create([
            'lat' => $data['location']['lat'],
            'lng' => $data['location']['lng'],
            'origin' => 'address'
        ]);
        $user->location()->associate($location);
        $user->save();

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
