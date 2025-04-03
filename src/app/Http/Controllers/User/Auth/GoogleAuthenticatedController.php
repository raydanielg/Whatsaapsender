<?php
namespace App\Http\Controllers\User\Auth;

use App\Models\User;
use App\Enums\SettingKey;
use App\Enums\StatusEnum;
use Illuminate\Support\Arr;
use App\Enums\Common\Status;
use App\Services\Core\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthenticatedController extends Controller
{
    public UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * redirectToGoogle
     *
     * @return mixed
     */
    public function redirectToGoogle(): mixed
    {
        $socialLogin = site_settings(SettingKey::SOCIAL_LOGIN_WITH->value);
        if (!$socialLogin) $this->unavailableResponse();

        $googleOauthStatus = Arr::get($socialLogin, "google_oauth.status");
        if (!$googleOauthStatus) $this->unavailableResponse();

        $isActive = $googleOauthStatus == StatusEnum::TRUE->status() ||
                        $googleOauthStatus == Status::ACTIVE->value;

        if(!$isActive) $this->unavailableResponse();

        return Socialite::driver('google')->redirect();
    }

    /**
     * unavailableResponse
     *
     * @param  $message
     * 
     * @return RedirectResponse
     */
    private function unavailableResponse($message = 'Currently, social login is unavailable'): RedirectResponse {

        $notify[] = ['error', translate($message)];
        return back()->withNotify($notify);
    }

    /**
     * @return RedirectResponse
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {

            $user = Socialite::driver('google')?->user();

        } catch (\Exception $e) {

            $notify[] = ["error", getEnvironmentMessage($e->getMessage())];
            return redirect('/')->withNotify($notify);
        }

        $authUser = User::active()
                            ->where('email', $user->email)
                            ->first();
        
        if(!$authUser) {

            $authUser                        = new User();
            $authUser->name                  = $user->name;
            $authUser->email                 = $user->email;
            $authUser->google_id             = $user->id;
            $authUser->email_verified_at     = carbon();
            $authUser->email_verified_code   = null;
            $authUser->email_verified_status = StatusEnum::TRUE->status();
            $authUser->save();
            $this->userService->applyOnboardingBonus($authUser);
        }
        Auth::login($authUser);

        return redirect(SettingKey::ROUTE_USER_DASHBOARD->value);
    }
}