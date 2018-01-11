<?php

namespace Hmazter\LaravelScheduleList;

class ScheduleEvent
{
    /**
     * @var string
     */
    private $expression;

    /**
     * @var string
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
    public function setExpression(string $expression): ScheduleEvent
    {
        $this->expression = $expression;
        return $this;
    }

    /**
     * @return string
     */
    public function getNextRunDate(): string
    {
        return $this->nextRunDate;
    }

    /**
     * @param string $nextRunDate
     * @return ScheduleEvent
     */
    public function setNextRunDate(string $nextRunDate): ScheduleEvent
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
    public function setShortCommand(string $shortCommand): ScheduleEvent
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
    public function setFullCommand(string $fullCommand): ScheduleEvent
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
    public function setDescription(string $description): ScheduleEvent
    {
        $this->description = $description;
        return $this;
    }
}