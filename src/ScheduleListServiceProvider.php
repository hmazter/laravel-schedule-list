<?php
declare(strict_types=1);

/**
 * Laravel Artisan Schedule List
 *
 * @author    Kristoffer Högberg <krihog@gmail.com>
 * @link      https://github.com/hmazter/laravel-schedule-list
 */

namespace Hmazter\LaravelScheduleList;

use Illuminate\Support\ServiceProvider;

class ScheduleListServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/schedule-list.php' => config_path('schedule-list.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/schedule-list.php', 'schedule-list');

        $this->commands([
            Console\ListScheduler::class
        ]);
    }
}
