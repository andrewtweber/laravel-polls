<?php

namespace Andrewtweber\Providers;

use Andrewtweber\Models\Poll;
use Andrewtweber\Observers\PollObserver;
use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

/**
 * Class PollsServiceProvider
 *
 * @package Andrewtweber\Providers
 */
class PollsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $config = realpath(__DIR__ . '/../../config/polls.php');
        $lang = realpath(__DIR__ . '/../../lang');
        $views = realpath(__DIR__ . '/../../resources/views');

        if ($this->app instanceof LaravelApplication) {
            $this->loadViewsFrom($views, 'snaccs');

            $this->publishes([
                $config => config_path('polls.php'),
                $lang => lang_path('polls'),
                $views => resource_path('views/vendor/snaccs'),
            ]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('polls');
        }

        $this->mergeConfigFrom($config, 'polls');

        Poll::observe(PollObserver::class);
    }
}
