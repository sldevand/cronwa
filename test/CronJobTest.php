<?php

namespace Test;

use App\CronJob;
use App\CronTasks;
use PHPUnit\Framework\TestCase;

/**
 * Class CronJobTest
 * @package Test
 */
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
                'activated' => true,
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

    /**
     * @throws \Exception
     */
    public function testParseWithComment()
    {
        $expected = new CronJob([
                'name' => 'test1',
                'description' => '',
                'activated' => false,
                'minute' => '*',
                'hour' => '*',
                'day' => '*',
                'month' => '*',
                'year' => '*',
                'command' => 'ls'
            ]
        );

        $cronJob = new CronJob(['name' => 'test1']);
        $cronJob->parse('#* * * * * ls');

        $this->assertEquals($expected, $cronJob);

    }
}

