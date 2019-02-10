<?php declare(strict_types = 1);

namespace Fousky\Component;

/**
 * Personal Identification Number utility for validations
 *
 * @author Lukáš Brzák <lukas.brzak@fousky.cz>
 */
class PINUtils
{
    public const SEX_FEMALE = 1;
    public const SEX_MALE = 2;

    /** @var string */
    protected $pin;

    /** @var int */
    protected $year;

    /** @var int */
    protected $month;

    /** @var int */
    protected $day;

    /** @var int */
    protected $sex;

    /** @var bool */
    protected $valid = false;

    public static function isValidPIN(string $pin): bool
    {
        return (new static($pin))->isValid();
    }

    public function __construct(string $pin)
    {
        $this->pin = $pin;

        $this->init();
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function getPin(): string
    {
        return $this->pin;
    }

    public function getBirthDateTime(): ?\DateTime
    {
        if (!$this->isValid()) {
            return null;
        }

        $date = \DateTime::createFromFormat('Y-m-d', "{$this->year}-{$this->month}-{$this->day}");
        $date->setTime(0, 0);

        return $date;
    }

    public function getYear(): ?int
    {
        if (!$this->valid) {
            return null;
        }

        return $this->year;
    }

    public function getMonth(): ?int
    {
        if (!$this->valid) {
            return null;
        }

        return $this->month;
    }

    public function getDay(): ?int
    {
        if (!$this->valid) {
            return null;
        }

        return $this->day;
    }

    public function getSex(): ?int
    {
        if (!$this->valid) {
            return null;
        }

        return $this->sex;
    }

    public function isFemale(): bool
    {
        if (!$this->valid) {
            return false;
        }

        return $this->sex === self::SEX_FEMALE;
    }

    public function isMale(): bool
    {
        if (!$this->valid) {
            return false;
        }

        return $this->sex === self::SEX_MALE;
    }

    /**
     * Inicializace dat rodného čísla
     *
     * @author David Grudl
     * @link https://phpfashion.com/jak-overit-platne-ic-a-rodne-cislo
     */
    private function init(): void
    {
        if (!preg_match('#^\s*(\d\d)(\d\d)(\d\d)[ /]*(\d\d\d)(\d?)\s*$#', $this->pin, $matches)) {
            $this->valid = false;
            return;
        }

        [, $this->year, $this->month, $this->day, $ext, $c] = $matches;

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
            $this->valid = checkdate((int) $this->month, (int) $this->day, (int) $this->year);
        }
    }
}
