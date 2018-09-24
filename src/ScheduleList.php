<?php
declare(strict_types=1);

namespace Hmazter\LaravelScheduleList;

use Illuminate\Console\Scheduling\CallbackEvent;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Collection;

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
     * @return Collection|ScheduleEvent[]
     */
    public function all(): Collection
    {
        $events = collect();

        /** @var Event $event */
        foreach ($this->schedule->events() as $event) {
            $fullCommand = $event->buildCommand();
            $mutexStatus = null;

            if ($event instanceof CallbackEvent) {
                $fullCommand = 'Closure' . $fullCommand;
            }

            if ($event->withoutOverlapping) {
                $mutexStatus = $event->mutex->exists($event);
            }

            $scheduleEvent = new ScheduleEvent(
                $event->getExpression(),
                $event->timezone,
                $fullCommand,
                $event->description ?? '',
                $mutexStatus
            );

            if (empty($scheduleEvent->getDescription()) && $scheduleEvent->getCommandName()) {
                $scheduleEvent->setDescription($this->getDescriptionFromCommand($scheduleEvent->getCommandName()));
            }

            $events[] = $scheduleEvent;
        }

        return $events;
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

        try {
            $className = get_class($commands[$commandName]);
            $reflection = new \ReflectionClass($className);
            return (string)$reflection->getDefaultProperties()['description'];
        } catch (\ReflectionException $exception) {
            return '';
        }
    }
}
