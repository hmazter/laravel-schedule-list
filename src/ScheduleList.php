<?php
declare(strict_types=1);

namespace Hmazter\LaravelScheduleList;

use Illuminate\Console\Scheduling\CallbackEvent;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;

class ScheduleList
{
    /**
     * @var ConsoleKernel
     */
    private $consoleKernel;

    /**
     * @param ConsoleKernel $consoleKernel
     */
    public function __construct(ConsoleKernel $consoleKernel)
    {
        $this->consoleKernel = $consoleKernel;
    }

    /**
     * @return array|ScheduleEvent[]
     */
    public function all(): array
    {
        $events = [];

        /** @var Event $event */
        foreach (app(Schedule::class)->events() as $event) {
            $fullCommand = $event->buildCommand();

            if ($event instanceof CallbackEvent) {
                $fullCommand = 'Closure' . $fullCommand;
            }

            $scheduleEvent = new ScheduleEvent(
                $event->getExpression(),
                $event->timezone,
                $fullCommand,
                $event->description ?? ''
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
