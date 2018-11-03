<?php

namespace App\Service;

use App\SocialAuth;
use Laravel\Socialite\Contracts\Provider;
use App\User;

class SocialAuthService
{
    public function setOrGetUser(Provider $provider)
    {
        $providerUser = $provider->user();
        $providerName = class_basename($provider);

        $account = SocialAuth::whereProvider($providerName)
            ->whereProviderUserId($providerUser->getId())
            ->first();

        if ($account) {
            return $account->user;
        } else {

            $account = new SocialAuth([
                'provider_user_id' => $providerUser->getId(),
                'provider' => $providerName
            ]);

            $user = User::whereEmail($providerUser->getEmail())->first();

            if (!$user) {
                $user = User::create([
                    'email' => $providerUser->getEmail(),
                    'name' => $providerUser->getName(),
                    // 'avatar' =>$providerUser->getAvatar(),
                    // 'username' => strtolower(preg_replace('/\s+/', '_', $providerUser->name) . mt_rand(10, 100))
                ]);
            }

            $account->user()->associate($user);
            $account->save();

            return $user;
        }

    }
}