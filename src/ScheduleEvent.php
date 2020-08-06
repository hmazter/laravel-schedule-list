<?php
declare(strict_types=1);

namespace Hmazter\LaravelScheduleList;

use Cron\CronExpression;
use Illuminate\Console\Parser;
use Illuminate\Support\Carbon;

class ScheduleEvent
{
    /**
     * @var string
     */
    private $expression;

    /**
     * @var \DateTimeZone|string|null
     */
    private $timezone;

    /**
     * @var string
     */
    private $fullCommand;

    /**
     * @var string
     */
    private $description;

    /**
     * @param string $expression
     * @param \DateTimeZone|null|string $timezone
     * @param string $fullCommand
     * @param string $description
     */
    public function __construct(
        string $expression,
        $timezone,
        string $fullCommand,
        string $description
    ) {
        $this->expression = $this->truncateCronExpression($expression);
        $this->timezone = $timezone;
        $this->fullCommand = $fullCommand;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getExpression(): string
    {
        return $this->expression;
    }

    /**
     * @return \DateTimeZone|null|string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * Get the next scheduled run date for this event
     *
     * @return Carbon
     */
    public function getNextRunDate(): Carbon
    {
        $cron = CronExpression::factory($this->getExpression());
        $nextRun = Carbon::instance($cron->getNextRunDate());
        if ($this->timezone) {
            $nextRun->setTimezone($this->timezone);
        }

        return $nextRun;
    }

    /**
     * @return string
     */
    public function getFullCommand(): string
    {
        return $this->fullCommand;
    }

    /**
     * Get the command with options and arguments from the full cron command
     * Remove php binary, "artisan" and output
     *
     * @return string
     */
    public function getShortCommand(): string
    {
        $command = $this->getFullCommand();
        $command = substr($command, 0, strpos($command, '>'));
        $command = trim(
            str_replace(
                config('schedule-list.remove_strings_from_command', [
                    "'" . PHP_BINARY . "'",
                    "'artisan'",
                ]),
                '',
                $command
            )
        );

        return $command;
    }

    /**
     * @return string
     */
    public function getCommandName(): string
    {
        $shortCommand = $this->getShortCommand();
        if (empty($shortCommand)) {
            return '';
        }
        list($commandName) = Parser::parse($shortCommand);
        return $commandName;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return ScheduleEvent
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Truncate the cron-expression to allow for Laravel <=5.5 that uses 6 positions for the expression
     *
     * @param string $expression
     * @return string
     */
    private function truncateCronExpression(string $expression): string
    {
        $expressionParts = preg_split('/\s/', $expression, -1, PREG_SPLIT_NO_EMPTY);
        if (count($expressionParts) === 5) {
            return $expression;
        }

        $expressionParts = array_slice($expressionParts, 0, 5);
        return implode(' ', $expressionParts);
    }
}
