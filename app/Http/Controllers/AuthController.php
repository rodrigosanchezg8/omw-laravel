<?php

namespace App\Http\Controllers;

use App\User;
use App\Company;
use App\Http\Requests\SignUpClient;
use App\Http\Requests\SignUpCompany;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function signup_client(SignUpClient $request)
    {
        $user = User::create($request->all());

        if ($request->profile_photo) {
            $this->uploadProfilePhoto();
        }

        return response()->json([
            'message' => 'User succesfully created',
            'user' => $user,
        ]);
    }

    public function signup_company(SignUpCompany $request)
    {
        $company = Company::create($request->all());

        if ($request->profile_image) {
            $this->uploadProfilePhoto();
        }

        return response()->json([
            'message' => 'Company succesfully created',
            'company' => $company,
        ]);
    }

    private function uploadProfilePhoto($fileableType, $fileableId, $image)
    {
        
    }
}
