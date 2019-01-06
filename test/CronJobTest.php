<?php

namespace Test;

use App\Controller\CronJobController;
use App\Cron\CronJob;
use App\Exception\CronJobException;
use InvalidArgumentException;
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

    public function testGetDatePart()
    {
        $cronJob = new CronJob(['name' => 'test']);
        $expected = "48 12 1 1 1-2";
        $entry = "48 12 1 1 1-2 sudo apt-get update -y && sudo apt-get upgrade";
        $entry = $cronJob->getActivationPart($entry);
        $entries = explode(" ", $entry);

        $this->assertEquals($expected, $cronJob->getDatePart($entries));
    }


    /**
     * @dataProvider cronGoodExpressionsProvider
     * @param string $strToTest
     * @throws CronJobException
     */
    public function testValidateGoodExpression($strToTest)
    {
        $this->assertTrue(CronJob::validate($strToTest));
    }

    /**
     * @dataProvider cronBadExpressionsProvider
     * @param string $strToTest
     */
    public function testValidateBadExpression($strToTest)
    {
        try {
            $this->assertFalse(CronJob::validate($strToTest));
        } catch (CronJobException $e) {
            $this->assertTrue($e instanceof CronJobException);
        }
    }

    /**
     * @return array
     */
    public function cronGoodExpressionsProvider()
    {
        return [
            ["* * * * *"]
        ];
    }

    /**
     * @return array
     */
    public function cronBadExpressionsProvider()
    {
        return [
            ["*****"],
            ["12,23 34 25 * *"]
        ];
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

    /**
     * @return array
     */
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

