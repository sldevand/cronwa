<?php
/**
 * Created by PhpStorm.
 * User: sebastien
 * Date: 28/12/18
 * Time: 00:11
 */

namespace App;


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
    public function addTask($task)
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
    public function getTask($name){
        if(!array_key_exists($name,$this->tasks)){
            throw new \Exception("Task $name doesn't exist");
        }

        return $this->tasks[$name];
    }
}
