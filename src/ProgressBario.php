<?php

namespace Balda38;

/**
 * Simple progress bar for your php CLI tasks. It's ProgressBario.
 *
 * Usage:
 * 1. init your ProgressBario instance;
 * 2. use ProgressBario `makeStep()` function on every iteration of your
 * `for`, `foreach` or `while` construction;
 * 3. use ProgressBario `close()` function after ending your `for`, `foreach`,
 * `while` constructions or where you want (for example in catch).
 *
 * ===========================EXAMPLE===========================
 * <code>
 * $countOfProcess = 1000;
 * $progressBario = new Balda38\ProgressBario($countOfProcess);
 * for ($i = 0; $i < $countOfProcess; ++$i) {
 *     sleep(1);
 *     $progressBario->nextStep();
 * }
 * $progressBario->close();
 * </code>
 */
class ProgressBario
{
    /**
     * Number of drawed elements of ProgressBario.
     */
    private const PROGRESS_SIZE = 50;

    /**
     * Total number of process;
     *
     * @var int
     */
    private $total;
    /**
     * External task name.
     *
     * @var string
     */
    private $taskName;
    /**
     * Current step of ProgressBario from total.
     *
     * @var int
     */
    private $step = 0;
    /**
     * Show used memory by task.
     *
     * @var bool
     */
    private $showMemoryUsage;
    /**
     * Time when task has been started.
     *
     * @var int
     */
    private $startTime;
    /**
     * Average step time;
     *
     * @var int
     */
    private $avgTime;

    public function __construct(
        int $total,
        string $taskName = '',
        bool $showTime = false,
        bool $showMemoryUsage = false
    ) {
        $this->total = $total;

        $this->taskName = $taskName;
        if ($taskName) {
            $taskName .= ' s';
        } else {
            $taskName = 'S';
        }
        echo $taskName.'tarted...'.PHP_EOL;

        if ($showTime) {
            $this->startTime = time();
        }
        $this->showMemoryUsage = $showMemoryUsage;
    }

    /**
     * Make next step of ProgressBario.
     */
    public function makeStep() : void
    {
        ++$this->step;
        $this->draw();
    }

    /**
     * Draw ProgressBario.
     */
    private function draw() : void
    {
        $rawPercents = ($this->step / $this->total) * 100;
        $percents = round($rawPercents, 2);

        $rawComplete = ($this->step / $this->total) * self::PROGRESS_SIZE;
        $complete = round($rawComplete);

        $notComplete = self::PROGRESS_SIZE - $complete;

        $completeStr = '';
        $notCompleteStr = '';
        while ($complete > 0) {
            --$complete;
            $completeStr.='█';
        }
        while ($notComplete > 0) {
            --$notComplete;
            $notCompleteStr.=' ';
        }

        echo
            "\r[".$completeStr.$notCompleteStr.'] '.
            number_format($percents, 2).'%'.
            ($this->startTime ? ' '.$this->getTime() : '').
            ($this->showMemoryUsage ? ' / Size of used memory: '.$this->getMemoryUsage().'Mb' : '')
        ;
    }

    /**
     * Get current remaining time.
     */
    private function getTime() : string
    {
        $now = time();
        $passTime = $now - $this->startTime;
        $endTime = $passTime * ($this->total / $this->step);
        $leftTime = $endTime - $passTime;

        if ($this->avgTime) {
            $leftTime = ($this->avgTime + $leftTime) / 2;
        }
        $this->avgTime = $leftTime;

        return 'Time left: ~ '.date('i', $leftTime).' minutes '.date('s', $leftTime).' seconds';
    }

    /**
     * Get current memory usage.
     */
    private function getMemoryUsage() : float
    {
        return round((float) (memory_get_usage() / 1024 / 1024), 2);
    }

    /**
     * Finish ProgressBario and print total info about task.
     */
    public function close() : void
    {
        $count = self::PROGRESS_SIZE;
        $str = '';
        while ($count > 0) {
            --$count;
            $str .= '█';
        }
        $output = "\r[".$str.']'.
            ($this->startTime ? ' '.$this->getFinalTime() : '').
            ($this->showMemoryUsage ? ' / Max size of used memory: '.$this->getFinalMemoryUsage().'Mb' : '').
            '          '.
            PHP_EOL
        ;
        echo $output;

        if ($this->taskName) {
            $finishMessage = $this->taskName.' f';
        } else {
            $finishMessage = 'F';
        }
        echo $finishMessage.'inished!'.PHP_EOL;
    }

    /**
     * Get total time of task of ProgressBario processed.
     */
    private function getFinalTime() : string
    {
        $diffTime = time() - $this->startTime;
        $minutes = date('i', $diffTime);
        $seconds = date('s', $diffTime);

        return $minutes.' minutes '.$seconds.' seconds have passed';
    }

    /**
     * Get peak memory usage on task of ProgressBario processed.
     */
    private function getFinalMemoryUsage() : float
    {
        return round((float) (memory_get_usage() / 1024 / 1024), 2);
    }
}
