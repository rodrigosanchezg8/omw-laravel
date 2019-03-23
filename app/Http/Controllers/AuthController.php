<?php

namespace App\Http\Controllers;

use App\Company;
use App\File;
use App\Http\Requests\Login;
use App\Http\Requests\SignUpClient;
use App\Http\Requests\SignUpCompany;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'message' => 'Succesfully logged out',
        ]);
    }

    public function signup_client(SignUpClient $request)
    {
        $user = User::create($request->all());

        $user->assignRole($request->role);

        if ($request->profile_photo) {
            $this->uploadProfilePhoto($user, $request->profile_photo);
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
            $this->uploadProfilePhoto($company, $request->profile_image);
        }

        return response()->json([
            'message' => 'Company succesfully created',
            'company' => $company,
        ]);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    private function uploadProfilePhoto($model, $image)
    {
        $subDirectory = get_class($model) == 'App\User' ? 'user_profile_photos' : 'company_profile_photos';

        $url = 'public/'. $subDirectory. '/'. $model->id. '_profile_photo.jpg';

        Storage::disk('local')->put($url, $image);

        $file = File::create([
            'name' => $model->id. '_profile_photo',
            'path' => $url,
        ]);

        DB::table('fileables')->insert([
            'file_id' => $file->id,
            'fileable_id' => $model->id,
            'fileable_type' => get_class($model),
            'description' => 'profile_image',
        ]);
    }
}
