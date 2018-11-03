<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use App\Service\SocialAuthService;

class SocialAuthController extends Controller
{
    public function redirect($social) {
        return Socialite::driver($social)->asPopup()->redirect();
    }

    public function callback(SocialAuthService $service, $social) {
        // $user = Socialite::with ( $service )->user ();
        // return view ('home.user')->withDetails($user)->withService($service);
        try {
            $user = $service->setOrGetUser(Socialite::driver($social));
            auth()->login($user);
            return redirect('/');
        } catch (\Exception $e) {
            return $e;
        }
    }
}
