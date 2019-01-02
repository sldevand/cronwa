<?php

namespace Test;

use App\Cron\CronTabs;
use PHPUnit\Framework\TestCase;

class CronTabsTest extends TestCase
{
    /**
     * @throws \App\Exception\CronJobException
     * @throws \App\Exception\CronTabsException
     */
    public function testFetchFromDirectory()
    {
        $dirName = __DIR__ . '/testfiles';

        $cronTabs = new CronTabs();
        $cronTabs->fetchFromDirectory($dirName);

        $this->assertTrue(count($cronTabs->getCronTabs()) === 1);

    }

}

