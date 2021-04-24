<?php

namespace App\Providers;

use App\Classes\AimlMatcher;
use App\Classes\AimlParser;
use App\Services\TalkService;
use Illuminate\Support\ServiceProvider;

class TalkServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TalkService::class, function ($app) {
            return new TalkService(config('lemur_tag'), new AimlMatcher(), new AIMLPArser());
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
