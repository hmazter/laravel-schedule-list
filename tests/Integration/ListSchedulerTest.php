<?php

use Illuminate\Contracts\Console\Kernel;

class ListSchedulerTest extends TestCase
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
        $consoleOutput = explode("\n", trim(\Illuminate\Support\Facades\Artisan::output()));
        $cron = \Cron\CronExpression::factory('0 10 * * *');

        self::assertStringContainsString('test:command:name', $consoleOutput[3]);
        self::assertStringContainsString('Description of event', $consoleOutput[3]);
        self::assertStringContainsString('0 10 * * *', $consoleOutput[3]);
        self::assertStringContainsString($cron->getNextRunDate()->format('Y-m-d H:i:s'), $consoleOutput[3]);

        // get description from the command class
        self::assertStringContainsString('test:command:two', $consoleOutput[4]);
        self::assertStringContainsString('Description of test command', $consoleOutput[4]);

        self::assertStringContainsString('ls -lah', $consoleOutput[5]);

        self::assertStringContainsString('0 13 * * *', $consoleOutput[6]);
        self::assertStringContainsString('Closure', $consoleOutput[6]);
        self::assertStringContainsString('A description for a scheduled callback', $consoleOutput[6]);

        self::assertStringContainsString('0 14 * * *', $consoleOutput[7]);
        self::assertStringContainsString('Closure', $consoleOutput[7]);
        self::assertStringContainsString('TestJob', $consoleOutput[7]);
    }

    public function testListSchedulerCommand_withTasksAndCronStyle()
    {
        \Illuminate\Support\Facades\Artisan::call('schedule:list', ['--cron' => true]);
        $consoleOutput = explode("\n", trim(\Illuminate\Support\Facades\Artisan::output()));

        self::assertStringContainsString('test:command:name', $consoleOutput[0]);
        self::assertStringContainsString('artisan', $consoleOutput[0]);
        self::assertStringContainsString('0 10 * * *', $consoleOutput[0]);
        self::assertStringContainsString((DIRECTORY_SEPARATOR === '\\') ? 'NUL' : '/dev/null', $consoleOutput[0]);

        self::assertStringContainsString('test:command:two', $consoleOutput[1]);

        self::assertStringContainsString('ls -lah', $consoleOutput[2]);

        self::assertStringContainsString('Closure', $consoleOutput[3]);

        self::assertStringContainsString('Closure', $consoleOutput[4]);
    }
}
