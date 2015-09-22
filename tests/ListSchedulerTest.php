<?php

use Hmazter\LaravelScheduleList\Console\ListScheduler;

class ListSchedulerTest extends PHPUnit_Framework_TestCase
{
    public function testClassExists()
    {
        $this->assertTrue(class_exists('Hmazter\LaravelScheduleList\Console\ListScheduler'));
    }

    public function testClassHasFireMethod()
    {
        $this->assertTrue(
            method_exists('Hmazter\LaravelScheduleList\Console\ListScheduler', 'handle') ||
            method_exists('Hmazter\LaravelScheduleList\Console\ListScheduler', 'fire'),
            'Handle or fire method is missing on the Command'
        );
    }

    public function testConstructor()
    {
        $schedule = $this->getMockBuilder('\Illuminate\Console\Scheduling\Schedule')->getMock();
        $command = new ListScheduler($schedule);

        $this->assertTrue($command instanceof ListScheduler, 'Created command is not correct class');
    }
}