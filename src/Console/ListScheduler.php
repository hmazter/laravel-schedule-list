<?php

namespace Hmazter\LaravelScheduleList\Console;

use Illuminate\Console\Command;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\Schedule;
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
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $events = $this->schedule->events();

        if (count($events) == 0) {
            $this->info('No tasks scheduled');
            return;
        }

        if ($this->option('cron')) {
            /** @var Event $event */
            foreach ($events as $event) {
                $this->line($event->getExpression() . ' ' . $event->buildCommand());
            }
        } else {
            $rows = [];
            /** @var Event $event */
            foreach ($events as $event) {
                $command = $event->buildCommand();
                $desc = $event->getSummaryForDisplay();

                // if php binary is present in string, it is a the actual command and not a description
                if (strpos($desc, PHP_BINARY) !== false) {
                    $desc = '';
                }

                if ($this->output->getVerbosity() == OutputInterface::VERBOSITY_NORMAL) {
                    $command = substr($command, 0, strpos($command, '>'));
                    $command = trim(str_replace([PHP_BINARY, '"artisan"'], '', $command));

                }

                $rows[] = [
                    'expression'  => $event->getExpression(),
                    'command'     => $command,
                    'description' => $desc,
                ];
            }
            $headers = array_keys($rows[0]);
            $this->table($headers, $rows);
        }
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
}