<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\Auth\AuthInterface;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthInterface $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register a new user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        $this->authService->register($request->all());
    }
    /**
     * Login a user.
     *
     * @param Request $request
     * @return JsonResponse
     */

    public function login(Request $request)
    {
        $this->authService->login($request->all());
    }

    /**
     * Logout a user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request)
    {
        $this->authService->logout($request);
    }
}
