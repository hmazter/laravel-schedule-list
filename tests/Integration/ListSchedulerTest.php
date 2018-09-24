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
        $consoleOutput = trim(\Illuminate\Support\Facades\Artisan::output());
        $consoleOutput = explode("\n", $consoleOutput);
        $cron = \Cron\CronExpression::factory('0 10 * * *');

        self::assertContains('test:command:name', $consoleOutput[3]);
        self::assertContains('Description of event', $consoleOutput[3]);
        self::assertContains('0 10 * * *', $consoleOutput[3]);
        self::assertContains($cron->getNextRunDate()->format('Y-m-d H:i:s'), $consoleOutput[3]);

        // get description from the command class
        self::assertContains('test:command:two', $consoleOutput[4]);
        self::assertContains('Description of test command', $consoleOutput[4]);

        self::assertContains('ls -lah', $consoleOutput[5]);

        self::assertContains('0 13 * * *', $consoleOutput[6]);
        self::assertContains('Closure', $consoleOutput[6]);
        self::assertContains('A description for a scheduled callback', $consoleOutput[6]);

        self::assertContains('0 14 * * *', $consoleOutput[7]);
        self::assertContains('Closure', $consoleOutput[7]);
        self::assertContains('TestJob', $consoleOutput[7]);

        self::assertContains('Not locked', $consoleOutput[8]);

        self::assertContains('Locked', $consoleOutput[9]);
        self::assertNotContains('Not locked', $consoleOutput[9]);
    }

    public function testListSchedulerCommand_withTasksAndCronStyle()
    {
        \Illuminate\Support\Facades\Artisan::call('schedule:list', ['--cron' => true]);
        $consoleOutput = trim(\Illuminate\Support\Facades\Artisan::output());
        $consoleOutput = explode("\n", $consoleOutput);

        self::assertContains('test:command:name', $consoleOutput[0]);
        self::assertContains('artisan', $consoleOutput[0]);
        self::assertContains('0 10 * * *', $consoleOutput[0]);
        self::assertContains((DIRECTORY_SEPARATOR === '\\') ? 'NUL' : '/dev/null', $consoleOutput[0]);

        self::assertContains('test:command:two', $consoleOutput[1]);

        self::assertContains('ls -lah', $consoleOutput[2]);

        self::assertContains('Closure', $consoleOutput[3]);

        self::assertContains('Closure', $consoleOutput[4]);
    }
}
