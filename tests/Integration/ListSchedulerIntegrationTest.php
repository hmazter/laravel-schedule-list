<?php

use Illuminate\Contracts\Console\Kernel;

class ListSchedulerIntegrationTest extends TestCase
{
    /**
     * Resolve application Console Kernel implementation.
     *
     * @param  \Illuminate\Foundation\Application $app
     */
    protected function resolveApplicationConsoleKernel($app)
    {
        $app->singleton(Kernel::class, MockConsoleKernel::class);
    }

    public function testListSchedulerCommand_withTasksAndTableStyle()
    {
        \Illuminate\Support\Facades\Artisan::call('schedule:list');
        $consoleOutput = trim(\Illuminate\Support\Facades\Artisan::output());
        $cron = \Cron\CronExpression::factory('0 10 * * *');

        self::assertContains('test:command:name', $consoleOutput);
        self::assertContains('Description of command', $consoleOutput);
        self::assertContains('0 10 * * *', $consoleOutput);
        self::assertContains($cron->getNextRunDate()->format('Y-m-d H:i:s'), $consoleOutput);
    }

    public function testListSchedulerCommand_withTasksAndCronStyle()
    {
        \Illuminate\Support\Facades\Artisan::call('schedule:list', ['--cron' => true]);
        $consoleOutput = trim(\Illuminate\Support\Facades\Artisan::output());

        self::assertContains('test:command:name', $consoleOutput);
        self::assertContains('artisan', $consoleOutput);
        self::assertContains('0 10 * * *', $consoleOutput);
        self::assertContains((DIRECTORY_SEPARATOR === '\\') ? 'NUL' : '/dev/null', $consoleOutput);
    }
}
