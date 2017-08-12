<?php

class MockConsoleKernel extends \Orchestra\Testbench\Console\Kernel
{
    protected $commands = [
        \TestCommand::class,
    ];

    protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule)
    {
        $schedule->command('test:command:name')->dailyAt('10:00')->description('Description of event');
        $schedule->command('test:command:two')->dailyAt('10:00');
        $schedule->exec('ls -lah')->mondays()->at('3:00');
    }
}
