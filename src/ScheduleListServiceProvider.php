<?php
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

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands('Hmazter\LaravelScheduleList\Console\ListScheduler');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['Hmazter\LaravelScheduleList\Console\ListScheduler'];
    }
}
