<?php

namespace App\Providers;

use App\Classes\LemurLog;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use DB;
use Log;
use Illuminate\Support\Facades\Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $environment = config('app.env');
        //on remote live and dev servers force https
        if($environment === 'production' || $environment === 'development') {
            $this->app['request']->server->set('HTTPS', true);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $environment = config('app.env');

        if ($environment === 'local') {
            // Add in boot function
            DB::listen(function ($query) {
                LemurLog::sql(
                    $query->sql,
                    $query->bindings,
                    $query->time
                );
            });
        }

        //on remote live and dev servers force https
        if($environment === 'production' || $environment === 'development') {
            URL::forceScheme('https');
        }
    }
}
