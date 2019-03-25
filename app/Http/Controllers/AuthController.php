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

class AuthController extends Controller
{
    public function login(Login $request)
    {
        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 400);
        }

        $user = $request->user();

        $role = $user->roles()->first()->name;

        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;

        if ($request->remember_me) {
            $token->expires_at = now()->addWeeks(1);
        }

        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString(),
            'role' => $role,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Se ha cerrado sesión.',
        ]);
    }

    public function signup_client(SignUpClient $request)
    {
        $user = User::create($request->all());
        $user->city()->associate($request->city_id);

        $user->assignRole($request->role['name']);

        if ($request->profile_image)
            File::upload_profile_photo($user, $request->profile_image);

        return response()->json([
            'status' => 200,
            'header' => 'Éxito',
            'message' => 'Usuario creado.',
            'user' => $user,
        ]);
    }

    public function signup_company(SignUpCompany $request)
    {
        $company = Company::create($request->all());

        if ($request->profile_image)
            File::upload_profile_photo($company, $request->profile_image);

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
