<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Contracts\Routing\UrlGenerator;
use App;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(UrlGenerator $url)
    {
        Schema::defaultStringLength(191);
        Resource::withoutWrapping();
        // if (config('app.env') === 'production') { $this->app['request']->server->set('HTTPS', true); }
        // if (App::environment('production')) {
        //     $url->forceScheme('https');
        // }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
