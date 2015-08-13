<?php
/**
 * Laravel Artisan Schedule List
 *
 * @author    Kristoffer Högberg <krihog@gmail.com>
 * @link      https://github.com/hmazter/laravel-schedule-list
 */

namespace Hmazter\LaravelScheduleList;

use Illuminate\Support\ServiceProvider;
use Hmazter\LaravelScheduleList\Console\ListScheduler;

class ScheduleListServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        var_dump('ScheduleListServiceProvider::boot');
        die;
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        var_dump('ScheduleListServiceProvider::register');
        die;

        $configPath = __DIR__ . '/../config/ide-helper.php';
        $this->mergeConfigFrom($configPath, 'ide-helper');
        
        $this->app['command.hmazter.schedule-list'] = $this->app->share(
            function ($app) {
                return new ListScheduler($app['schedule']);
            }
        );

        $this->commands('command.hmazter.schedule-list');
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
//        return ['Hmazter\LaravelScheduleList\ListScheduler'];
        return array('command.hmazter.schedule-list');
    }
}
