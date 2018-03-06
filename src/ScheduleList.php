<?php
declare(strict_types=1);

namespace Hmazter\LaravelScheduleList;

use Carbon\Carbon;
use Cron\CronExpression;
use Illuminate\Console\Parser;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;

class ScheduleList
{
    /**
     * @var Schedule
     */
    private $schedule;

    /**
     * @var ConsoleKernel
     */
    private $consoleKernel;

    /**
     * @param Schedule $schedule
     * @param ConsoleKernel $consoleKernel
     */
    public function __construct(Schedule $schedule, ConsoleKernel $consoleKernel)
    {
        $this->schedule = $schedule;
        $this->consoleKernel = $consoleKernel;
    }

    /**
     * @return array|ScheduleEvent[]
     */
    public function all(): array
    {
        $events = [];

        /** @var Event $event */
        foreach ($this->schedule->events() as $event) {
            $fullCommand = $event->buildCommand();
            $shortCommand = $this->getShortCommand($fullCommand);
            list($commandName) = Parser::parse($shortCommand);
            $description = $event->description;
            if (empty($description)) {
                $description = $this->getDescriptionFromCommand($commandName);
            }

            $scheduleEvent = new ScheduleEvent();
            $scheduleEvent
                ->setExpression($event->getExpression())
                ->setDescription($description)
                ->setNextRunDate($this->getNextRunDate($event))
                ->setFullCommand($fullCommand)
                ->setShortCommand($shortCommand);

            $events[] = $scheduleEvent;
        }

        return $events;
    }

    /**
     * Get the "short" command
     * Remove php binary, "artisan" and std output from the command string
     *
     * @param string $command
     * @return string
     */
    public function getShortCommand(string $command): string
    {
        $command = substr($command, 0, strpos($command, '>'));
        $command = trim(str_replace(["'".PHP_BINARY."'", "'artisan'"], '', $command));
        return $command;
    }

    /**
     * Parse the description from the Command instead of the scheduled event
     *
     * @param string $commandName
     * @return string
     */
    private function getDescriptionFromCommand(string $commandName): string
    {
        $commands = $this->consoleKernel->all();
        if (!isset($commands[$commandName])) {
            return '';
        }

        $className = get_class($commands[$commandName]);
        $reflection = new \ReflectionClass($className);
        return (string)$reflection->getDefaultProperties()['description'];
    }

    /**
     * Get the next scheduled run date for this event
     *
     * @param Event $event
     * @return string
     */
    private function getNextRunDate(Event $event): string
    {
        $cron = CronExpression::factory($event->getExpression());
        $date = Carbon::now();

        if ($event->timezone) {
            $date->setTimezone($event->timezone);
        }

        return $cron->getNextRunDate()->format('Y-m-d H:i:s');
    }
}
