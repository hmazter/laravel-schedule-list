<?php

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
}