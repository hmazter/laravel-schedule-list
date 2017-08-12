<?php

namespace Hmazter\LaravelScheduleList\Console;

use Carbon\Carbon;
use Cron\CronExpression;
use Illuminate\Console\Command;
use Illuminate\Console\Parser;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListScheduler extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'schedule:list';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all scheduled commands in the task scheduler';

    /**
     * @var Schedule
     */
    protected $schedule;

    /**
     * Create a new command instance.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     */
    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;

        parent::__construct();
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['cron', null, InputOption::VALUE_NONE, 'Show output cron style', null],
        ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $events = $this->schedule->events();

        if (count($events) === 0) {
            $this->info('No tasks scheduled');
            return;
        }

        if ($this->option('cron')) {
            $this->outputCronStyle($events);
            return;
        }

        $this->outputTableStyle($events);
    }

    /**
     * Backwards compatibility for laravel <5.5
     */
    public function fire()
    {
        $this->handle();
    }

    /**
     * @param $events
     */
    protected function outputCronStyle($events)
    {
        /** @var Event $event */
        foreach ($events as $event) {
            $this->line($event->getExpression() . ' ' . $event->buildCommand());
        }
    }

    /**
     * @param $events
     */
    protected function outputTableStyle($events)
    {
        $rows = [];
        /** @var Event $event */
        foreach ($events as $event) {
            $fullCommand = $event->buildCommand();
            $shortCommand = $this->getShortCommand($fullCommand);
            $isVerbosityNormal = $this->output->getVerbosity() === OutputInterface::VERBOSITY_NORMAL;
            list($commandName) = Parser::parse($shortCommand);
            $description = $event->description;
            if (empty($description)) {
                $description = $this->getDescriptionFromCommand($commandName);
            }

            $rows[] = [
                'expression' => $event->getExpression(),
                'next run at' => $this->getNextRunDate($event),
                'command' => $isVerbosityNormal ? $shortCommand : $fullCommand,
                'description' => $description,
            ];
        }

        $headers = array_keys($rows[0]);
        $this->table($headers, $rows);
    }

    /**
     * Get the "short" command
     * Remove php binary, "artisan" and std output from the command string
     *
     * @param string $command
     * @return string
     */
    private function getShortCommand($command)
    {
        $command = substr($command, 0, strpos($command, '>'));
        $command = trim(str_replace([PHP_BINARY, 'artisan', '\'', '"'], '', $command));
        return $command;
    }

    /**
     * Parse the description from the Command instead of the scheduled event
     *
     * @param string $commandName
     * @return string
     */
    private function getDescriptionFromCommand($commandName)
    {
        try {
            $className = get_class($this->getApplication()->find($commandName));
            $reflection = new \ReflectionClass($className);
            return (string)$reflection->getDefaultProperties()['description'];
        } catch (CommandNotFoundException $e) {
            return '';
        }
    }

    /**
     * Get the next scheduled run date for this event
     *
     * @param Event $event
     * @return string
     */
    private function getNextRunDate($event)
    {
        $cron = CronExpression::factory($event->getExpression());
        $date = Carbon::now();

        if ($event->timezone) {
            $date->setTimezone($event->timezone);
        }

        return $cron->getNextRunDate()->format('Y-m-d H:i:s');
    }
}
