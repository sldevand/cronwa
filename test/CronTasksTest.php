<?php

namespace Test;

use App\CronJob;
use App\CronTasks;
use PHPUnit\Framework\TestCase;

class CronTasksTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testAddTask()
    {
        $cronJob = new CronJob(
            [
                'name' => 'test1',
                'description' => 'Test 1 Description',
                'minute' => '5',
                'hour' => '*',
                'day' => '1',
                'month' => '*',
                'year' => '2018'
            ]
        );
        $cronJob2 = new CronJob(
            [
                'name' => 'test2',
                'description' => 'Test 2 Description',
                'minute' => '34',
                'hour' => '3',
                'day' => '*',
                'month' => '*',
                'year' => '*'
            ]
        );

        $cronTasks = new CronTasks();

        $cronTasks
            ->addTask($cronJob)
            ->addTask($cronJob2);

        $this->assertTrue(count($cronTasks->getTasks()) === 2, "There are not 2 tasks");
        $this->assertEquals($cronJob, $cronTasks->getTask('test1'));
        $this->assertEquals($cronJob2, $cronTasks->getTask('test2'));
        $this->assertNotEquals($cronJob, $cronTasks->getTask('test2'));
        $this->assertNotEquals($cronJob2, $cronTasks->getTask('test1'));
    }
}

