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
    public function boot(): void
    {
        $config = realpath(__DIR__ . '/../../config/polls.php');
        $lang = realpath(__DIR__ . '/../../lang');
        $migrations = realpath(__DIR__ . '/../database/migrations');
        $views = realpath(__DIR__ . '/../../resources/views');

        if ($this->app instanceof LaravelApplication) {
            $this->loadMigrationsFrom($migrations);
            $this->loadTranslationsFrom($lang, 'polls');
            $this->loadViewsFrom($views, 'polls');

            $this->publishes([
                $config => config_path('polls.php'),
                $lang => lang_path('polls'),
                $migrations => database_path('migrations'),
                $views => resource_path('views/vendor/laravel-polls'),
            ]);
        /** @phpstan-ignore-next-line */
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('polls');
        }

        $this->mergeConfigFrom($config, 'polls');

        Poll::observe(PollObserver::class);
    }
}
