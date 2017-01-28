<?php

class MockConsoleKernel extends \Orchestra\Testbench\Console\Kernel
{
    protected function schedule(\Illuminate\Console\Scheduling\Schedule $schedule)
    {
        $schedule->command('test:command:name')->dailyAt('10:00')->description('Description of command');
    }
}
