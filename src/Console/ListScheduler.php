<?php
declare(strict_types=1);

namespace Hmazter\LaravelScheduleList\Console;

use Hmazter\LaravelScheduleList\ScheduleEvent;
use Hmazter\LaravelScheduleList\ScheduleList;
use Illuminate\Console\Command;
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
     * @var ScheduleList
     */
    protected $scheduleList;

    /**
     * @param ScheduleList $scheduleList
     */
    public function __construct(ScheduleList $scheduleList)
    {
        $this->scheduleList = $scheduleList;

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
     */
    public function handle()
    {
        $events = $this->scheduleList->all();

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
     * @param array|ScheduleEvent[] $events
     */
    protected function outputCronStyle($events)
    {
        foreach ($events as $event) {
            $this->line($event->getExpression() . ' ' . $event->getFullCommand());
        }
    }

    /**
     * @param array|ScheduleEvent[] $events
     */
    protected function outputTableStyle($events)
    {
        $isVerbosityNormal = $this->output->getVerbosity() === OutputInterface::VERBOSITY_NORMAL;
        $rows = [];
        foreach ($events as $event) {
            $rows[] = [
                'expression' => $event->getExpression(),
                'next run at' => $event->getNextRunDate()->format('Y-m-d H:i:s'),
                'command' => $isVerbosityNormal ? $event->getShortCommand() : $event->getFullCommand(),
                'description' => $event->getDescription(),
            ];
        }

        $headers = array_keys($rows[0]);
        $this->table($headers, $rows);
    }
}
