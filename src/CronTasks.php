<?php

namespace App;

use App\Exception\CrontaskException;

/**
 * Class CronTasks
 * @package App
 */
class CronTasks
{
    /**
     * @var CronJob[] $tasks
     */
    protected $tasks;

    /**
     * CronTasks constructor.
     * @param CronJob[] $tasks
     */
    public function __construct($tasks = [])
    {
        $this->tasks = $tasks;
    }


    /**
     * @param string $fileName
     * @return bool|string
     * @throws CrontaskException
     * @throws \Exception
     */
    public function fetchFromFile($fileName)
    {
        if (!file_exists($fileName)) {
            throw new CrontaskException("$fileName doesn't exist");
        }

        $lines = file($fileName);

        $nameRegex = '/^(#){4} /';
        $cronJob = new CronJob();
        foreach ($lines as $line) {
            if (preg_match($nameRegex, $line)) {
                $cronJob = new CronJob();
                $cronJob->setName(trim(preg_replace($nameRegex, '', $line)));
                $this->saveTask($cronJob);
            } elseif ($line !== PHP_EOL){
                $cronJob->parse($line);
                $this->saveTask($cronJob);
            }
        }


        return $this->tasks;

    }

    /**
     * @param string $fileName
     */
    public function saveToFile($fileName)
    {

    }

    /**
     * @return CronJob[]
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * @param CronJob[] $tasks
     * @return CronTasks
     */
    public function setTasks($tasks)
    {
        $this->tasks = $tasks;
        return $this;
    }

    /**
     * @param CronJob $task
     * @return CronTasks
     */
    public function saveTask($task)
    {
        $this->tasks[$task->getName()] = $task;

        return $this;
    }

    /**
     * @param string $name
     * @return CronTasks
     */
    public function removeTask($name)
    {
        unset($this->tasks[$name]);

        return $this;
    }

    /**
     * @param $name
     * @return CronJob|mixed
     * @throws \Exception
     */
    public function getTask($name)
    {
        if (!array_key_exists($name, $this->tasks)) {
            throw new \Exception("Task $name doesn't exist");
        }

        return $this->tasks[$name];
    }
}
