<?php
declare(strict_types=1);

namespace Hmazter\LaravelScheduleList\Console;

use Hmazter\LaravelScheduleList\ScheduleEvent;
use Hmazter\LaravelScheduleList\ScheduleList;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
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

        if ($events->isEmpty()) {
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
     * @param Collection|ScheduleEvent[] $events
     */
    protected function outputCronStyle(Collection $events)
    {
        foreach ($events as $event) {
            $this->line($event->getExpression() . ' ' . $event->getFullCommand());
        }
    }

    /**
     * @param Collection|ScheduleEvent[] $events
     */
    protected function outputTableStyle($events)
    {
        $isVerbosityNormal = $this->output->getVerbosity() === OutputInterface::VERBOSITY_NORMAL;
        $usesMutexLocks = $events->reduce(function (bool $carry, ScheduleEvent $event) {
            return $carry || $event->getMutexStatus() !== null;
        }, false);

        $rows = [];
        foreach ($events as $event) {
            $row = [
                'expression' => $event->getExpression(),
                'next run at' => $event->getNextRunDate()->format('Y-m-d H:i:s'),
                'command' => $isVerbosityNormal ? $event->getShortCommand() : $event->getFullCommand(),
                'description' => $event->getDescription(),
            ];
            if ($usesMutexLocks) {
                $row['overlapping lock'] = $event->getOverlappingLockDescription();
            }
            $rows[] = $row;
        }

        $headers = array_keys($rows[0]);
        $this->table($headers, $rows);
    }
}
