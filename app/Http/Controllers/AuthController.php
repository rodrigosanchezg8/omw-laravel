<?php

namespace App\Http\Controllers;

use App\Company;

use App\File;
use App\Http\Requests\Login;
use App\Http\Requests\SignUpClient;
use App\Http\Requests\SignUpCompany;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Login $request)
    {
        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => ['Usuario no autorizado'],
            ], 400);
        }

        $user = $request->user();

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me) {
            $token->expires_at = now()->addWeeks(1);
        }

        $token->save();

        return response()->json([
            'header' => 'Éxito',
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'status' => 200,
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString(),
            'user' => $user->load('roles')
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Session has been closed',
        ]);
    }

    public function signup_client(SignUpClient $request)
    {
        try {
            $user = User::create($request->all());
            $user->city()->associate($request->city_id);

            $user->assignRole($request->role);

            if ($request->profile_image) {
                File::upload_profile_photo($user, $request->profile_image);
            }

            return response()->json([
                'status' => 200,
                'header' => 'Éxito',
                'message' => 'Usuario creado.',
                'user' => $user,
            ]);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
    }

    public function signup_company(SignUpCompany $request)
    {
        $company = Company::create($request->all());

        if ($request->profile_image) {
            File::upload_profile_photo($company, $request->profile_image);    
        }

        return response()->json([
            'message' => 'Compañia creada exitosamente.',
            'company' => $company,
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}
