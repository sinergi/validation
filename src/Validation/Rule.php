<?php
namespace Sinergi\Validation;

class Rule
{
    const LENGHT = 'LENGHT';
    const MIN_LENGHT = 'MIN_LENGHT';
    const MAX_LENGHT = 'MAX_LENGHT';
    const NUMERIC = 'NUMERIC';
    const EMAIL = 'EMAIL';
    const NOT_EMPTY = 'NOT_EMPTY';

    /**
     * @var string
     */
    private $rule;

    /**
     * @var array
     */
    private $params;

    /**
     * @param string $rule
     * @param array $params
     */
    public function __construct($rule, array $params = null)
    {
        $this->rule = $rule;
        $this->params = $params;
    }

    /**
     * @param string $rule
     * @return $this
     */
    public function setRule($rule)
    {
        $this->rule = $rule;
        return $this;
    }

    /**
     * @return string
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function assert($value)
    {
        if ($this->rule === self::LENGHT) {
            return $this->assertLenght($value);
        }
        return null;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private function assertLenght($value)
    {
        if (is_object($value) || is_array($value)) {
            return false;
        }

        $strlen = strlen($value);
        if (!isset($this->params['max']) && $strlen === $this->params['lenght']) {
            return true;
        } elseif (isset($this->params['max']) && $strlen >= $this->params['lenght'] && $strlen <= $this->params['max']) {
            return true;
        }
        return false;
    }

    /**
     * @param int $lenght
     * @param int|null $max
     * @return Rule
     */
    public static function lenght($lenght, $max = null)
    {
        return new self(self::LENGHT, ['lenght' => $lenght, 'max' => $max]);
    }

    /**
     * @param int $min
     * @return Rule
     */
    public static function minLenght($min)
    {
        return new self(self::MIN_LENGHT, ['min' => $min]);
    }

    /**
     * @param int $max
     * @return Rule
     */
    public static function maxLenght($max)
    {
        return new self(self::MAX_LENGHT, ['max' => $max]);
    }

    /**
     * @return Rule
     */
    public static function numeric()
    {
        return new self(self::NUMERIC);
    }

    /**
     * @return Rule
     */
    public static function email()
    {
        return new self(self::EMAIL);
    }

    /**
     * @return Rule
     */
    public static function notEmpty()
    {
        return new self(self::NOT_EMPTY);
    }
}