<?php

class ListSchedulerNoScheduleTest extends TestCase
{
    public function testListSchedulerCommand_withNoTasks()
    {
        \Illuminate\Support\Facades\Artisan::call('schedule:list');
        $consoleOutput = trim(\Illuminate\Support\Facades\Artisan::output());

        self::assertContains('No tasks scheduled', $consoleOutput);
    }
}
