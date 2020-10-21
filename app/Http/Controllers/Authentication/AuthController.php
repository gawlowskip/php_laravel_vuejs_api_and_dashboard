<?php

namespace App\Http\Controllers\Authentication;

use App\Http\Controllers\ApiController;
use App\Http\Requests\LocaleRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Session;
use App\Traits\ApiResponse;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use stdClass;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Carbon\Carbon;

class AuthController extends ApiController
{
    use ApiResponse;

    /**
     * POST /api/register
     * Register new User
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->only([
            'name',
            'last_name',
            'email',
            'facebook_id'
        ]);
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;

        User::create($data);

        if (!$token = auth()->attempt($request->only('email', 'password'))) {
            return $this->errorResponse(trans('auth.failed'), 401);
        }

        /* Store login in sessions table */
        Session::create([
            'id' => Str::uuid(),
            'type' => Session::TYPE_API,
            'user_id' => $request->user()->id,
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'payload' => uniqid(),
            'last_activity' => Carbon::now()->timestamp
        ]);

        return (new UserResource($request->user()))->additional([
            'meta' => [
                'token' => $token
            ]
        ])->response()->setStatusCode(201);
    }

    /**
     * POST /api/login
     * Login User
     *
     * @param LoginRequest $request
     * @return UserResource|\Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(LoginRequest $request)
    {
        $data = $request->only([
            'email',
            'password'
        ]);

        $this->checkUserCredentials($data);

        $email = $request->email;
        $password = $request->password;
        if (!$token = auth()->attempt(['email' => $email, 'password' => $password])) {
            return $this->errorResponse(trans('auth.failed'), 401);
        }

        /* Store login in sessions table */
        Session::create([
            'id' => Str::uuid(),
            'type' => Session::TYPE_API,
            'user_id' => $request->user()->id,
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'payload' => uniqid(),
            'last_activity' => Carbon::now()->timestamp
        ]);

        return (new UserResource($request->user()))->additional([
            'meta' => [
                'token' => $token
            ]
        ]);
    }

    /**
     * POST /api/admin/login
     * Login only for Admins
     *
     * @param LoginRequest $request
     * @return UserResource|\Illuminate\Http\JsonResponse
     */
    public function adminLogin(LoginRequest $request) {
        $data = $request->only([
            'email',
            'password'
        ]);

        $this->checkAdminCredentials($data);

        $email = $request->email;
        $password = $request->password;
        if (!$token = auth()->attempt(['email' => $email, 'password' => $password])) {
            return $this->errorResponse(trans('auth.failed'), 401);
        }

        /* Store login in sessions table */
        Session::create([
            'id' => Str::uuid(),
            'type' => Session::TYPE_API,
            'user_id' => $request->user()->id,
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'payload' => uniqid(),
            'last_activity' => Carbon::now()->timestamp
        ]);

        return (new UserResource($request->user()))->additional([
            'meta' => [
                'token' => $token
            ]
        ]);
    }

    /**
     * POST /api/logout
     * Logout
     */
    public function logout()
    {
        if (!auth()->check()) {
            return response()->setStatusCode(200);
        }

        /* Store logout in sessions table */
        Session::create([
            'id' => Str::uuid(),
            'type' => Session::TYPE_API,
            'user_id' => null,
            'ip_address' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'payload' => uniqid(),
            'last_activity' => Carbon::now()->timestamp
        ]);
        auth()->logout();
    }

    /**
     * GET /api/user
     * Get the currently authenticated user
     *
     * @param Request $request
     * @return UserResource
     */
    public function user(Request $request)
    {
        $user = $request->user();

        return new UserResource($user);
    }

    /**
     * GET /api/payload
     * Get the raw JWT payload
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function payload()
    {
        $payload = new stdClass;
        $payload->data = auth()->payload();

        return $this->successResponse($payload, 200);
    }

    /**
     * POST /api/locale
     * Set API locale
     *
     * @param LocaleRequest $request
     */
    public function setLocale(LocaleRequest $request)
    {
        $locale = $request->get('locale');

        if (in_array($locale, config()->get('app.locales'))) {
            session(['locale' => $locale]);
        }
    }

    /**
     * Check User credentials
     *
     * @param array $data
     */
    private function checkUserCredentials(array $data)
    {
        $this->user = User::where('email', $data['email'])->first();

        if (empty($this->user) || !Hash::check($data['password'], $this->user->password)) {
            throw new HttpException(401, trans('auth.failed'));
        }
    }

    /**
     * Check Admin credentials
     *
     * @param array $data
     */
    private function checkAdminCredentials(array $data)
    {
        $this->user = User::where('email', $data['email'])->first();

        if (empty($this->user) || !Hash::check($data['password'], $this->user->password) || !$this->user->is_admin) {
            throw new HttpException(401, trans('auth.failed'));
        }
    }
}
