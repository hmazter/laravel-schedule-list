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

        self::assertContains('test:command:name', $consoleOutput[3]);
        self::assertContains('Description of event', $consoleOutput[3]);
        self::assertContains('0 10 * * *', $consoleOutput[3]);
        self::assertContains($cron->getNextRunDate()->format('Y-m-d H:i:s'), $consoleOutput[3]);

        // get description from the command class
        self::assertContains('test:command:two', $consoleOutput[4]);
        self::assertContains('Description of test command', $consoleOutput[4]);

        self::assertContains('ls -lah', $consoleOutput[5]);

        self::assertContains('6 position cron', $consoleOutput[6]);
    }

    public function testListSchedulerCommand_withTasksAndCronStyle()
    {
        \Illuminate\Support\Facades\Artisan::call('schedule:list', ['--cron' => true]);
        $consoleOutput = trim(\Illuminate\Support\Facades\Artisan::output());

        self::assertContains('test:command:name', $consoleOutput);
        self::assertContains('artisan', $consoleOutput);
        self::assertContains('0 10 * * *', $consoleOutput);
        self::assertContains((DIRECTORY_SEPARATOR === '\\') ? 'NUL' : '/dev/null', $consoleOutput);

        self::assertContains('test:command:two', $consoleOutput);

        self::assertContains('ls -lah', $consoleOutput);
    }
}
