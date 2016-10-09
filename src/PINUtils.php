<?php

namespace Fousky\Component;

/**
 * Personal Identification Number utility for validations
 *
 * @author Lukáš Brzák <lukas.brzak@aquadigital.cz>
 */
class PINUtils
{
    // ženy mají přednost :-)
    const SEX_FEMALE = 1;
    const SEX_MALE = 2;

    /** @var string $pin */
    protected $pin;

    /** @var int $year */
    protected $year;

    /** @var int $month */
    protected $month;

    /** @var int $day */
    protected $day;

    /** @var int $sex */
    protected $sex;

    /** @var bool $valid */
    protected $valid = false;

    /**
     * Je rodné číslo validní?
     *
     * @param string $pin
     *
     * @return bool
     */
    public static function isValidPIN($pin)
    {
        return (new static($pin))->isValid();
    }

    /**
     * @param string $pin
     */
    public function __construct($pin)
    {
        $this->pin = $pin;

        $this->init();
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * @return string
     */
    public function getPin()
    {
        return $this->pin;
    }

    /**
     * @return \DateTime|false
     */
    public function getBirthDateTime()
    {
        if (!$this->valid) {
            return false;
        }

        $date = \DateTime::createFromFormat('Y-m-d', "{$this->year}-{$this->month}-{$this->day}");
        $date->setTime(0, 0, 0);

        return $date;
    }

    /**
     * @return int|false
     */
    public function getYear()
    {
        if (!$this->valid) {
            return false;
        }

        return $this->year;
    }

    /**
     * @return int|false
     */
    public function getMonth()
    {
        if (!$this->valid) {
            return false;
        }

        return $this->month;
    }

    /**
     * @return int|false
     */
    public function getDay()
    {
        if (!$this->valid) {
            return false;
        }

        return $this->day;
    }

    /**
     * @return int|false
     */
    public function getSex()
    {
        if (!$this->valid) {
            return false;
        }

        return $this->sex;
    }

    /**
     * @return bool|false
     */
    public function isFemale()
    {
        if (!$this->valid) {
            return false;
        }

        return $this->sex === self::SEX_FEMALE;
    }

    /**
     * @return bool|false
     */
    public function isMale()
    {
        if (!$this->valid) {
            return false;
        }

        return $this->sex === self::SEX_MALE;
    }

    /**
     * Inicializace dat rodného čísla
     *
     * @return void
     *
     * @author David Grudl
     * @see ( https://phpfashion.com/jak-overit-platne-ic-a-rodne-cislo )
     */
    private function init()
    {
        if (!preg_match('#^\s*(\d\d)(\d\d)(\d\d)[ /]*(\d\d\d)(\d?)\s*$#', $this->pin, $matches)) {
            $this->valid = false;
            return;
        }

        list(, $this->year, $this->month, $this->day, $ext, $c) = $matches;

        $valid = null;

        if ($c === '') {
            $this->year += $this->year < 54 ? 1900 : 1800;
        } else {
            $mod = ($this->year . $this->month . $this->day . $ext) % 11;

            if ($mod === 10) {
                $mod = 0;
            }

            if ($mod !== (int) $c) {
                $this->valid = false;
                $valid = false;
            }

            $this->year += $this->year < 54 ? 2000 : 1900;
        }

        $this->sex = self::SEX_MALE;

        // k měsíci může být připočteno 20 (muž), 50 nebo 70 (žena)
        if ($this->month > 70 && $this->year > 2003) {

            $this->month -= 70;
            $this->sex = self::SEX_FEMALE;

        } else if ($this->month > 50) {

            $this->month -= 50;
            $this->sex = self::SEX_FEMALE;

        } else if ($this->month > 20 && $this->year > 2003) {
            $this->month -= 20;
        }

        if ($valid !== false) {
            $this->valid = checkdate($this->month, $this->day, $this->year);
        }
    }
}
