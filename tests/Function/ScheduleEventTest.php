<?php
declare(strict_types=1);

use Hmazter\LaravelScheduleList\ScheduleEvent;
use Illuminate\Support\Carbon;

class ScheduleEventTest extends TestCase
{
    /**
     * @test
     */
    public function getShortCommand_doesNotStripQuoteMarkFromTheCommands()
    {
        $php = PHP_BINARY;
        $command = "'$php' 'artisan' test:hello --name=\"John Doe\" > /dev/null";

        $scheduleEvent = new ScheduleEvent(
            '',
            null,
            $command,
            ''
        );

        self::assertEquals('test:hello --name="John Doe"', $scheduleEvent->getShortCommand());
    }

    /**
     * @test
     */
    public function getNextRunDate_withoutTimezone_returnsNextRunDate()
    {
        $scheduleEvent = new ScheduleEvent(
            '* * * * *',
            null,
            '',
            ''
        );

        $nextMinute = Carbon::parse('+1 minute')->format('Y-m-d H:i:00');

        self::assertEquals($nextMinute, $scheduleEvent->getNextRunDate()->format('Y-m-d H:i:s'));
    }

    /**
     * @test
     */
    public function getNextRunDate_withTimezone_returnsRunDateAdjustedForTheTimezone()
    {

        $scheduleEvent = new ScheduleEvent(
            '0 8 * * *',
            'Etc/GMT+2',
            '',
            ''
        );

        // 8 am GMT+2 should be 6 am from a UTC perspective
        // Meaning the server (hypothetically running UTC) should trigger the job at 6 am
        // that happens at 8 am GMT+2 then.
        self::assertContains('06:00:00', $scheduleEvent->getNextRunDate()->format('Y-m-d H:i:s'));
    }

    /**
     * @test
     */
    public function scheduleEvent_with6PositionCronExpression_truncatesCronExpressionAndReturnsNextRunDate()
    {
        $scheduleEvent = new ScheduleEvent(
            '* * * * * *',
            null,
            '',
            ''
        );

        self::assertEquals('* * * * *', $scheduleEvent->getExpression());
        self::assertInstanceOf(Carbon::class, $scheduleEvent->getNextRunDate());
    }

    /** @test */
    public function getShortCommand_removesStringsProvidedThroughConfig()
    {
        config(['schedule-list.remove_strings_from_command' => [
            "'/usr/bin/php'",
            "'art'",
        ]]);

        $command = "'/usr/bin/php' 'art' test:hello --name=\"John Doe\" > /dev/null";

        $scheduleEvent = new ScheduleEvent(
            '',
            null,
            $command,
            ''
        );

        self::assertEquals('test:hello --name="John Doe"', $scheduleEvent->getShortCommand());
    }
}