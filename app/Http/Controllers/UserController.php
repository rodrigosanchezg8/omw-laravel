<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientStore;
use App\Http\Requests\ClientUpdate;
use App\Services\UserService;
use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        try {

            return response()->json($this->service->list($request->role));

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function store(ClientStore $request)
    {
        try {

            $user = $this->service->store($request->all());

            return response()->json([
                'status' => 'success',
                'user' => $user,
            ]);

        } catch (Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function show($user_id)
    {
        try {

            $user = $this->service->getDetailedUser($user_id);

            return response()->json($user);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function update(User $user, ClientUpdate $request)
    {
        try {
            $this->service->update($user, $request->all());

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function delete(User $user)
    {
        try {

            $this->service->delete($user);

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }
}
