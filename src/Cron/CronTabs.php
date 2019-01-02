<?php

namespace App\Cron;

use App\Exception\CronTabsException;

/**
 * Class CronTabs
 * @package App
 */
class CronTabs
{
    /**
     * @var CronTab[] $cronTabs
     */
    protected $cronTabs;

    /**
     * CronTabs constructor.
     * @param CronTab[] $cronTabs
     */
    public function __construct($cronTabs = [])
    {
        $this->cronTabs = $cronTabs;
    }

    /**
     * @param $dirName
     * @throws CronTabsException
     * @throws Exception\CronJobException
     */
    public function fetchFromDirectory($dirName)
    {
        if (!is_dir($dirName)) {
            throw new CronTabsException("$dirName is not a directory!");
        }

        $d = dir($dirName);

        while (false !== ($entry = $d->read())) {
            if (!preg_match('/^(\.)\w|(\.){1,2}$/', $entry)) {
                $name = explode('.', $entry)[1];
                $cronTab = new CronTab($name);
                $cronTab->fetchFromFile($dirName . '/' . $entry);
                $this->saveCronTab($cronTab);
            }

        }
        $d->close();
    }

    /**
     * @return CronTab[]
     */
    public function getCronTabs()
    {
        return $this->cronTabs;
    }

    /**
     * @param string $name
     * @return CronTab
     * @throws CronTabsException
     */
    public function getCronTab($name)
    {
        $this->exists($name);

        return $this->cronTabs[$name];
    }

    /**
     * @param array $cronTabs
     * @return CronTabs
     */
    public function setCronTabs(array $cronTabs)
    {
        $this->cronTabs = $cronTabs;

        return $this;
    }

    /**
     * @param CronTab $cronTab
     * @return CronTabs
     */
    public function saveCronTab($cronTab)
    {
        $this->cronTabs[$cronTab->getName()] = $cronTab;

        return $this;
    }

    /**
     * @param string $name
     * @return CronTabs
     * @throws CronTabsException
     */
    public function removeCronTab($name)
    {
        $this->exists($name);
        unset($this->cronTabs[$name]);

        return $this;
    }

    /**
     * @param string $name
     * @throws CronTabsException
     */
    public function exists($name)
    {
        if (!array_key_exists($name, $this->cronTabs)) {
            throw new CronTabsException("$name CronTab doesn't exist!");
        }
    }
}