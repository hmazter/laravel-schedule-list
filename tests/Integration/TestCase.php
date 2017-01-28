<?php

class TestCase extends Orchestra\Testbench\TestCase
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            Hmazter\LaravelScheduleList\ScheduleListServiceProvider::class
        ];
    }
}
