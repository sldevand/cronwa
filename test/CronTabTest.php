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
    public function testSaveJob()
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
     * @dataProvider expectedJobsProvider
     * @throws \App\Exception\CronJobException
     */
    public function testFetchFromFile($expJobs)
    {
        $cronTab = new CronTab('test');
        $result = $cronTab->fetchFromFile(__DIR__ . '/testfiles/crontab.test.txt');

        $this->assertEquals($expJobs, $result);
    }

    /**
     * @dataProvider expectedJobsProvider
     * @param $expJobs
     * @throws \App\Exception\CronJobException
     */
    public function testSaveToFile($expJobs)
    {

        $filePath = __DIR__ . '/testfiles/crontab.test2.txt';

        if (file_exists($filePath)) unlink($filePath);

        $cronTab = new CronTab('test', $expJobs);

        $expected = <<<EXP
#### Test 1
59 19,12 * * * ls -la

#### Test 2
#00,01 20,13 * * * ls -l

#### Test 3
04,05,06 20,13 * * * sudo curl "http://192.168.1.52/dashboard/resultat.php?actionid=1&val=0"


EXP;

        $result = $cronTab->saveToFile($filePath);
        $this->assertEquals($expected, $result);

        $jobs = $cronTab->fetchFromFile($filePath);
        $this->assertEquals($expJobs, $jobs);

    }

    public function expectedJobsProvider()
    {
        $cronJobs = [
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

        return [[$cronJobs]];
    }

}

