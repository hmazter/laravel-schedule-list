<?php

class MockConsoleKernel extends \Orchestra\Testbench\Console\Kernel
{
    protected $commands = [
        \TestCommand::class,
    ];

    protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule)
    {
        $closure = function () {
            echo 'callback or invokable class';
        };

        $schedule->command('test:command:name')->dailyAt('10:00')->description('Description of event');
        $schedule->command('test:command:two')->dailyAt('10:00')->timezone('UTC');
        $schedule->exec('ls -lah')->mondays()->at('3:00');
        $schedule->call($closure)->dailyAt('13:00')->description('A description for a scheduled callback');
        $schedule->job(new \TestJob())->dailyAt('14:00');
        $schedule->exec('ls')->daily()->withoutOverlapping();
        $event = $schedule->exec('cd')->daily()->withoutOverlapping();
        $event->mutex->create($event);
    }
}
