<?php

namespace Test;

use App\CronJob;
use App\CronTab;
use PHPUnit\Framework\TestCase;

class CronTabTest extends TestCase
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

        $cronTab = new CronTab('test');

        $cronTab
            ->saveJob($cronJob)
            ->saveJob($cronJob2);

        $this->assertTrue(count($cronTab->getJobs()) === 2, "There are not 2 jobs");
        $this->assertEquals($cronJob, $cronTab->getJob('test1'));
        $this->assertEquals($cronJob2, $cronTab->getJob('test2'));
        $this->assertNotEquals($cronJob, $cronTab->getJob('test2'));
        $this->assertNotEquals($cronJob2, $cronTab->getJob('test1'));
    }

    /**
     * @throws \App\Exception\CronJobException
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


        $cronTab = new CronTab('test');
        $result = $cronTab->fetchFromFile("testfiles/crontab.test.txt");


        $this->assertEquals($expected, $result);
    }

    public function testSaveToFile()
    {
        $cronTab = new CronTab('test', [
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
        ]);

        $expected = "#### Test 1\n59 19,12 * * * ls -la\n#### Test 2\n#00,01 20,13 * * * ls -l\n#### Test 3\n04,05,06 20,13 * * * sudo curl \"http://192.168.1.52/dashboard/resultat.php?actionid=1&val=0\"\n";

        $result = $cronTab->saveToFile('testfiles/crontab.test2.txt');
        $this->assertEquals($expected, $result);


    }

}

