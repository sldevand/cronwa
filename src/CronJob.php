<?php

namespace App;

class CronJob
{
    protected $name;
    protected $description;
    protected $activated;
    protected $minute;
    protected $hour;
    protected $day;
    protected $month;
    protected $year;
    protected $command;

    public function __construct(
        $data = array(
            'name' => 'default',
            'description' => '',
            'activated' => false,
            'minute' => '*',
            'hour' => '*',
            'day' => '*',
            'month' => '*',
            'year' => '*',
            'command' => ''
        )
    )
    {
        $this->hydrate($data);
    }

    public function hydrate($data)
    {
        foreach ($data as $attribute => $value) {
            $method = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $attribute)));

            if (is_callable(array($this, $method))) {
                $this->$method($value);
            }
        }
    }

    /**
     * @param $entry
     * @return CronJob
     * @throws \Exception
     */
    public function parse($entry)
    {
        if($entry[0] === "#"){
            $this->setActivated(false);
        }

        $entries = explode(" ", $entry);

        if (!count($entries) === 6) {
            throw new \Exception("Cannot parse '$entry' CronJob entry");
        }

        $entries['minute'] = $entries[0];
        $entries['hour'] = $entries[1];
        $entries['day'] = $entries[2];
        $entries['month'] = $entries[3];
        $entries['year'] = $entries[4];
        $entries['command'] = $entries[5];


        $this->hydrate($entries);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return CronJob
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     * @return CronJob
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMinute()
    {
        return $this->minute;
    }

    /**
     * @param mixed $minute
     * @return CronJob
     */
    public function setMinute($minute)
    {
        $this->minute = $minute;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * @param mixed $hour
     * @return CronJob
     */
    public function setHour($hour)
    {
        $this->hour = $hour;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param mixed $day
     * @return CronJob
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param mixed $month
     * @return CronJob
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     * @return CronJob
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param mixed $command
     * @return CronJob
     */
    public function setCommand($command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActivated()
    {
        return $this->activated;
    }

    /**
     * @param mixed $activated
     * @return CronJob
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;

        return $this;
    }

}
