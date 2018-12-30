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
    public function testSaveTask()
    {
        $cronJob = new CronJob(
            [
                'name' => 'test1',
                'description' => 'Test 1 Description',
                'minute' => '5',
                'hour' => '*',
                'day' => '1',
                'month' => '*',
                'dayOfWeek' => '2018'
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
                'dayOfWeek' => '*'
            ]
        );

        $cronTasks = new CronTasks();

        $cronTasks
            ->saveTask($cronJob)
            ->saveTask($cronJob2);

        $this->assertTrue(count($cronTasks->getTasks()) === 2, "There are not 2 tasks");
        $this->assertEquals($cronJob, $cronTasks->getTask('test1'));
        $this->assertEquals($cronJob2, $cronTasks->getTask('test2'));
        $this->assertNotEquals($cronJob, $cronTasks->getTask('test2'));
        $this->assertNotEquals($cronJob2, $cronTasks->getTask('test1'));
    }

    /**
     * @throws \App\Exception\CrontaskException
     */
    public function testFetchFromFile()
    {
        $expected = [
            'Test 1' =>
                new CronJob(
                    [
                        'name' => 'Test 1',
                        'description' => '',
                        'activated' => true,
                        'minute' => '59',
                        'hour' => '19,12',
                        'day' => '*',
                        'month' => '*',
                        'dayOfWeek' => '*',
                        'command' => 'ls -la'
                    ]),
            'Test 2' =>
                new CronJob(
                    [
                        'name' => 'Test 2',
                        'description' => '',
                        'activated' => false,
                        'minute' => '00,01',
                        'hour' => '20,13',
                        'day' => '*',
                        'month' => '*',
                        'dayOfWeek' => '*',
                        'command' => 'ls -l'
                    ]),
            'Test 3' =>
                new CronJob(
                    [
                        'name' => 'Test 3',
                        'description' => '',
                        'activated' => true,
                        'minute' => '04,05,06',
                        'hour' => '20,13',
                        'day' => '*',
                        'month' => '*',
                        'dayOfWeek' => '*',
                        'command' => 'sudo curl "http://192.168.1.52/dashboard/resultat.php?actionid=1&val=0"'
                    ])
        ];


        $cronTasks = new CronTasks();
        $result = $cronTasks->fetchFromFile("testfiles/crontab.test.txt");


        $this->assertEquals($expected, $result);

    }


}

