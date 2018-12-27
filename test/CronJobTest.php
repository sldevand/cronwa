<?php

namespace Test;

use App\CronJob;
use App\CronTasks;
use PHPUnit\Framework\TestCase;

class CronJobTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testParse()
    {
        $expected = new CronJob([
                'name' => 'test1',
                'description' => '',
                'minute' => '*',
                'hour' => '*',
                'day' => '*',
                'month' => '*',
                'year' => '*',
                'command' => 'ls'
            ]
        );

        $cronJob = new CronJob(['name' => 'test1']);
        $cronJob->parse('* * * * * ls');

        $this->assertEquals($expected, $cronJob);

    }
}

