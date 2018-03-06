<?php
declare(strict_types=1);

namespace Hmazter\LaravelScheduleList;

use Illuminate\Support\Carbon;

class ScheduleEvent
{
    /**
     * @var string
     */
    private $expression;

    /**
     * @var Carbon
     */
    private $nextRunDate;

    /**
     * @var string
     */
    private $shortCommand;

    /**
     * @var string
     */
    private $fullCommand;

    /**
     * @var string
     */
    private $description;

    /**
     * @return string
     */
    public function getExpression(): string
    {
        return $this->expression;
    }

    /**
     * @param string $expression
     * @return ScheduleEvent
     */
    public function setExpression(string $expression): self
    {
        $this->expression = $expression;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getNextRunDate(): Carbon
    {
        return $this->nextRunDate;
    }

    /**
     * @param Carbon $nextRunDate
     * @return ScheduleEvent
     */
    public function setNextRunDate(Carbon $nextRunDate): self
    {
        $this->nextRunDate = $nextRunDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getShortCommand(): string
    {
        return $this->shortCommand;
    }

    /**
     * @param string $shortCommand
     * @return ScheduleEvent
     */
    public function setShortCommand(string $shortCommand): self
    {
        $this->shortCommand = $shortCommand;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullCommand(): string
    {
        return $this->fullCommand;
    }

    /**
     * @param string $fullCommand
     * @return ScheduleEvent
     */
    public function setFullCommand(string $fullCommand): self
    {
        $this->fullCommand = $fullCommand;
        return $this;
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
}
