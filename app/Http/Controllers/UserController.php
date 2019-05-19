<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStore;
use App\Http\Requests\UserUpdate;
use App\User;
use App\Services\UserService;
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

    public function store(UserStore $request)
    {
        try {

            $user = $this->service->store($request->all());

            return response()->json([
                'status' => 200,
                'header' => 'Éxito',
                'message' => 'Usuario creado.',
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

    public function showClientByEmail(request $request)
    {
        try {

            $user = $this->service->getDetailedClientByEmail($request['email']);

            return response()->json([
                'header' => 'Cliente Encontrado',
                'status' => 'success',
                'client' => $user,
            ], 200);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }

    public function update(User $user, UserUpdate $request)
    {
        try {

            $user = $this->service->update($user, $request->all());

            return response()->json([
                'status' => 200,
                'header' => 'Éxito',
                'message' => 'Usuario actualizado.',
                'user' => $user,
            ]);

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

            return response()->json([
                'header' => 'Usuario Eliminado',
                'status' => 'success',
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage(),
            ]);

        }
    }
}
