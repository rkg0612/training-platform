<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RoleUser;
use App\Models\User;
use App\Models\UserLoggedInLog;
use Hash;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use JWTAuth;
use JWTFactory;
use Mockery\Exception;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    use SendsPasswordResetEmails, ResetsPasswords {
        SendsPasswordResetEmails::broker insteadof ResetsPasswords;
        ResetsPasswords::credentials insteadof SendsPasswordResetEmails;
    }

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => ['login', 'sendPasswordResetLink', 'callResetPassword', 'checkToken']]);
    }

    public function login(Request $request)
    {
        // grab credentials from the request
        $credentials = $request->only('email', 'password');
        $user = User::withoutGlobalScopes()->where('email', $request->input('email'))->first();

        if (empty($user)) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

        try {
            if ($user->status != User::ACTIVE) {
                $errorKey = strtolower($user->status);

                return response()->json(['error' => $errorKey], 401);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'invalid_credentials'], 401);
        }

        try {
            // attempt to verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong whilst attempting to encode the token
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        event(new Login(auth()->guard('api'), auth()->user(), null));

        $this->createLogForUserLogIn(auth()->user());

        // all good so return the token
        return $this->respondWithToken($token);
    }

    /*  *
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $user = auth()->user();
        $isAccountManagerOrDealer = $user->roles()->pluck('name')->contains(function ($role) {
            return in_array($role, [
                Role::SPECIFIC_DEALER_MANAGER,
                Role::ACCOUNT_MANAGER,
            ]);
        });
        $user->roles = $user->load('roles');
        $user->dealer = $user->load('dealer');
        $user->aws_url = env('AWS_URL');

        return response()->json($user);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => JWTFactory::getTtl() * 60,
        ]);
    }

    /**
     * Send password reset link.
     */
    public function sendPasswordResetLink(Request $request)
    {
        return $this->sendResetLinkEmail($request);
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response()->json([
            'message' => 'Password reset email sent.',
            'data' => $response,
        ]);
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response()->json(['message' => 'We could not find your account with that information. Please click the sign up link below to create a new one.'], 404);
    }

    /**
     * Handle reset password.
     */
    public function callResetPassword(Request $request)
    {
        return $this->reset($request);
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->password = Hash::make($password);
        $user->save();

        event(new PasswordReset($user));
    }

    /**
     * Get the response for a successful password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetResponse(Request $request, $response)
    {
        return response()->json(['message' => 'Password reset successfully.']);
    }

    /**
     * Get the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        return response()->json(['message' => 'Failed, Invalid Token.'], 422);
    }

    public function checkToken(Request $request)
    {
        if (empty($request->token)) {
            abort(401);
        }

        try {
            auth()->setToken($request->token)->getPayload();
        } catch (JWTException $e) {
            abort(401);
        }
    }

    private function createLogForUserLogIn(?\Illuminate\Contracts\Auth\Authenticatable $user)
    {
        $userId = $user->id;
        $dayRange = [now()->subDay(), now()];
        $check = UserLoggedInLog::where('user_id', $userId)
            ->whereBetween('created_at', $dayRange)
            ->get();

        if ($check->isNotEmpty()) {
            return;
        }

        $model = new UserLoggedInLog();
        $model->user_id = $userId;
        $model->save();
    }
}
