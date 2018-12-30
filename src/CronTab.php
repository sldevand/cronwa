<?php

namespace App;

use App\Exception\CronJobException;

/**
 * Class CronJobs
 * @package App
 */
class CronTab
{
    /**
     * @var string $name
     */
    protected $name;

    /**
     * @var CronJob[] $jobs
     */
    protected $jobs;

    /**
     * CronJobs constructor.
     * @param string $name
     * @param CronJob[] $jobs
     */
    public function __construct($name, $jobs = [])
    {
        $this->name = $name;
        $this->jobs = $jobs;
    }

    /**
     * @param string $fileName
     * @return bool|string
     * @throws CronJobException
     * @throws \Exception
     */
    public function fetchFromFile($fileName)
    {
        if (!file_exists($fileName)) {
            throw new CronJobException("$fileName doesn't exist");
        }

        $lines = file($fileName);

        $nameRegex = '/^(#){4} /';
        $cronJob = new CronJob();
        foreach ($lines as $line) {
            if (preg_match($nameRegex, $line)) {
                $cronJob = new CronJob();
                $cronJob->setName(trim(preg_replace($nameRegex, '', $line)));
                $this->saveJob($cronJob);
            } elseif ($line !== PHP_EOL) {
                $cronJob->parse($line);
                $this->saveJob($cronJob);
            }
        }

        return $this->jobs;
    }

    /**
     * @param string $fileName
     * @return string
     */
    public function saveToFile($fileName)
    {
        $content = '';
        foreach ($this->jobs as $job) {
          $content.=$job->__toString();
        }

        return $content;
    }

    /**
     * @return CronJob[]
     */
    public function getJobs()
    {
        return $this->jobs;
    }

    /**
     * @param CronJob[] $jobs
     * @return CronTab
     */
    public function setJobs($jobs)
    {
        $this->jobs = $jobs;
        return $this;
    }

    /**
     * @param CronJob $job
     * @return CronTab
     */
    public function saveJob($job)
    {
        $this->jobs[$job->getName()] = $job;

        return $this;
    }

    /**
     * @param string $name
     * @return CronTab
     */
    public function removeJob($name)
    {
        unset($this->jobs[$name]);

        return $this;
    }

    /**
     * @param $name
     * @return CronJob|mixed
     * @throws \Exception
     */
    public function getJob($name)
    {
        if (!array_key_exists($name, $this->jobs)) {
            throw new \Exception("Job $name doesn't exist");
        }

        return $this->jobs[$name];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return CronTab
     */
    public function setName(string $name)
    {
        $this->name = $name;
        return $this;
    }

}
