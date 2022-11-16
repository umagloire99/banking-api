<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Register user account
     * @param RegisterRequest $request
     * @return Response|Application|ResponseFactory
     */
    public function register(RegisterRequest $request): Response|Application|ResponseFactory
    {
        $input = $request->only('name', 'email');
        $input['password'] = Hash::make($request->get('password'));
        $user = User::create($input);
        $role = Role::whereName('user')->first();
        $user->roles()->attach($role);
        return response([
            'message' => __('general.account_created'),
        ], 201);
    }

    /**
     * generate new access token
     * @param LoginRequest $request
     * @return Response|Application|ResponseFactory
     */
    public function login(LoginRequest $request): Response|Application|ResponseFactory
    {
        $email = $request->get('email');
        $password = $request->get('password');
        if (!Auth::attempt(['email' => $email, 'password' => $password])) {
            return response([
                'message' => __('auth.failed')
            ], 401);
        } else {
            $user = User::whereEmail($email)->first();
            $user->tokens->each(function ($token) {
                if ($token->name == 'USER_SIDE') {
                    $token->delete();
                }
            });
            $token = $user->createToken('USER_SIDE', ['user-side']);
            return response([
                'message' => __('general.successful_login'),
                'user' => new UserResource($user),
                'access_token' => $token->accessToken,
            ]);
        }
    }

    /**
     * revoke the user_side token attached to the user
     * @param Request $request
     * @return Response|Application|ResponseFactory
     */
    public function logout(Request $request): Response|Application|ResponseFactory
    {
        $user = $request->user();
        $user->tokens->each(function ($token) {
           if ($token->name == 'USER_SIDE') {
               $token->delete();
           }
        });
        return response([
            'message' => __('general.successful_logout')
        ]);
    }
}
