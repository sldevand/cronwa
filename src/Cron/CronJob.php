<?php

namespace App\Cron;

use App\Exception\CronJobException;

/**
 * Class CronJob
 * @package App
 */
class CronJob
{
    protected $name = 'default';
    protected $description = '';
    protected $activated = false;
    protected $minute;
    protected $hour;
    protected $day;
    protected $month;
    protected $dayOfWeek;
    protected $command;

    /**
     * CronJob constructor.
     * @param array $data
     */
    public function __construct(
        $data = array(
            'name' => 'default',
            'description' => '',
            'activated' => false,
            'minute' => '*',
            'hour' => '*',
            'day' => '*',
            'month' => '*',
            'dayOfWeek' => '*',
            'command' => ''
        )
    )
    {
        $this->hydrate($data);
    }

    /**
     * @param $data
     */
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
     * @throws CronJobException
     */
    public function parse($entry)
    {

        $entry = $this->getActivationPart($entry);
        $entries = explode(" ", $entry);

        if (count($entries) < 6) {
            throw new CronJobException("Cannot parse '$entry' CronJob entry");
        }

        $datePart = $this->getDatePart($entries);

        if (!self::validate($datePart)) {
            throw new CronJobException("$datePart Is not a valid cron expression!");
        }

        $cronjob = [];
        $cronjob['minute'] = $entries[0];
        $cronjob['hour'] = $entries[1];
        $cronjob['day'] = $entries[2];
        $cronjob['month'] = $entries[3];
        $cronjob['dayOfWeek'] = $entries[4];
        $entries = array_slice($entries, 5);
        $cronjob['command'] = trim(implode(' ', $entries));

        $this->hydrate($cronjob);

        return $this;
    }

    /**
     * @param array $entries
     * @return string
     */
    public function getDatePart($entries)
    {
        $datePartArr = array_slice($entries, 0, 5);
        return implode(" ", $datePartArr);
    }

    /**
     * @param string $entry
     * @return CronJob|string
     */
    public function getActivationPart($entry)
    {
        if ($entry[0] === "#") {
            $this->setActivated(false);
            return substr($entry, 1);
        }
        $this->setActivated(true);

        return $entry;
    }

    /**
     * @param string $datePart
     * @return bool
     * @throws CronJobException
     */
    public static function validate($datePart)
    {
        try {
            $expression = \Sivaschenko\Utility\Cron\ExpressionFactory::getExpression($datePart);
            return $expression->isValid();
        } catch (\Exception $e) {
            throw new CronJobException($e->getMessage());
        }
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
    public function getDayOfWeek()
    {
        return $this->dayOfWeek;
    }

    /**
     * @param mixed $dayOfWeek
     * @return CronJob
     */
    public function setDayOfWeek($dayOfWeek)
    {
        $this->dayOfWeek = $dayOfWeek;

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

    /**
     * Returns the full objet with name and cron expression
     * @return string
     */
    public function __toString()
    {
        $activatedTag = '';
        if (!$this->activated) $activatedTag = '#';

        $cronExpression = $this->toCronExpression();

        return <<<STR
#### $this->name
$activatedTag$cronExpression


STR;
    }

    /**
     * Returns the full cron expression line with command
     * @return string
     */
    public function toCronExpression()
    {

        $cronDatePart = $this->toCronDatePart();

        return <<<STR
$cronDatePart $this->command
STR;
    }


    public function toCronDatePart()
    {
        return <<<STR
$this->minute $this->hour $this->day $this->month $this->dayOfWeek
STR;
    }

}
