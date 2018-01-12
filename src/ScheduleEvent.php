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
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @param string $expression
     * @return ScheduleEvent
     */
    public function setExpression($expression)
    {
        $this->expression = $expression;
        return $this;
    }

    /**
     * @return string
     */
    public function getNextRunDate()
    {
        return $this->nextRunDate;
    }

    /**
     * @param string $nextRunDate
     * @return ScheduleEvent
     */
    public function setNextRunDate($nextRunDate)
    {
        $this->nextRunDate = $nextRunDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getShortCommand()
    {
        return $this->shortCommand;
    }

    /**
     * @param string $shortCommand
     * @return ScheduleEvent
     */
    public function setShortCommand($shortCommand)
    {
        $this->shortCommand = $shortCommand;
        return $this;
    }

    /**
     * @return string
     */
    public function getFullCommand()
    {
        return $this->fullCommand;
    }

    /**
     * @param string $fullCommand
     * @return ScheduleEvent
     */
    public function setFullCommand($fullCommand)
    {
        $this->fullCommand = $fullCommand;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return ScheduleEvent
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }
}