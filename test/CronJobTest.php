<?php

namespace Test;

use App\CronJob;
use App\CronTab;
use PHPUnit\Framework\TestCase;

/**
 * Class CronJobTest
 * @package Test
 */
class CronJobTest extends TestCase
{
    /**
     * @dataProvider jobsProvider
     * @param CronJob $expected
     * @param string $parseStr
     * @throws \Exception
     */
    public function testParse($expected, $parseStr)
    {
        $cronJob = new CronJob(['name' => 'test']);
        $cronJob->parse($parseStr);

        $this->assertEquals($expected, $cronJob);

    }

    public function testToString()
    {
        $expected = <<<EXP
#### test1
* * * * * ls


EXP;
        $cronJob = $this->createCronJob('test1');

        $this->assertEquals($expected, $cronJob->__toString());

    }

    public function jobsProvider()
    {
        $cronJob = $this->createCronJob('test');
        $cronJob2 = clone $cronJob;
        $cronJob2->setActivated(false);

        return [
            [
                $cronJob->getName() => $cronJob,
                '* * * * * ls'
            ],
            [
                $cronJob->getName() => $cronJob2,
                '#* * * * * ls'
            ]
        ];
    }

    /**
     * @param string $name
     * @return CronJob
     */
    public function createCronJob($name)
    {
        return new CronJob([
                'name' => $name,
                'description' => '',
                'activated' => true,
                'minute' => '*',
                'hour' => '*',
                'day' => '*',
                'month' => '*',
                'dayOfWeek' => '*',
                'command' => 'ls'
            ]
        );
    }
}

