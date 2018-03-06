<?php
declare(strict_types=1);

use Hmazter\LaravelScheduleList\ScheduleList;

class ScheduleListTest extends TestCase
{

    /**
     * @test
     */
    public function get_short_command_does_not_strip_quote_mark_from_the_commands()
    {
        $scheduleMock = $this->createMock(\Illuminate\Console\Scheduling\Schedule::class);
        $consoleMock = $this->createMock(\Illuminate\Contracts\Console\Kernel::class);
        $scheduleList = new ScheduleList($scheduleMock, $consoleMock);

        $php = PHP_BINARY;
        $fullCommand = "'$php' 'artisan' test:hello --name=\"John Doe\" > '/dev/null' 2>&1";

        self::assertEquals('test:hello --name="John Doe"', $scheduleList->getShortCommand($fullCommand));
    }
}