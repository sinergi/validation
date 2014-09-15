<?php
namespace Sinergi\Validation;

use InvalidArgumentException;

class Rule
{
    const LENGHT = 'LENGHT';
    const MIN_LENGHT = 'MIN_LENGHT';
    const MAX_LENGHT = 'MAX_LENGHT';
    const NUMERIC = 'NUMERIC';
    const EMAIL = 'EMAIL';
    const NOT_EMPTY = 'NOT_EMPTY';
    const UNIQUE = 'UNIQUE';
    const REGEX = 'REGEX';
    const IN_ARRAY = 'IN_ARRAY';
    const EQUALS_ATTRIBUTE = 'EQUALS_ATTRIBUTE';

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
     * @param mixed $value
     * @param array $relatedValues
     * @return mixed
     */
    public function assert($value, array $relatedValues = null)
    {
        if ($this->rule === self::LENGHT) {
            return $this->assertLenght($value);
        } elseif ($this->rule === self::NOT_EMPTY) {
            return $this->assertNotEmpty($value);
        } elseif ($this->rule === self::MIN_LENGHT) {
            return $this->assertMinLenght($value);
        } elseif ($this->rule === self::MAX_LENGHT) {
            return $this->assertMaxLenght($value);
        } elseif ($this->rule === self::EMAIL) {
            return $this->assertEmail($value);
        } elseif ($this->rule === self::NUMERIC) {
            return $this->assertNumeric($value);
        } elseif ($this->rule === self::REGEX) {
            return $this->assertRegex($value);
        } elseif ($this->rule === self::UNIQUE) {
            return $this->assertUnique($value);
        } elseif ($this->rule === self::IN_ARRAY) {
            return $this->assertInArray($value);
        } elseif ($this->rule === self::EQUALS_ATTRIBUTE) {
            return $this->assertEqualsAttribute($value, $relatedValues);
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
     * @param mixed $value
     * @return bool
     */
    private function assertMinLenght($value)
    {
        if (is_object($value) || is_array($value)) {
            return false;
        }

        if (strlen($value) >= $this->params['min']) {
            return true;
        }
        return false;
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
     * @param mixed $value
     * @return bool
     */
    private function assertMaxLenght($value)
    {
        if (is_object($value) || is_array($value)) {
            return false;
        }

        if (strlen($value) <= $this->params['max']) {
            return true;
        }
        return false;
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
     * @param mixed $value
     * @return bool
     */
    private function assertNumeric($value)
    {
        return is_numeric($value);
    }

    /**
     * @return Rule
     */
    public static function numeric()
    {
        return new self(self::NUMERIC);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private function assertEmail($value)
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @return Rule
     */
    public static function email()
    {
        return new self(self::EMAIL);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private function assertNotEmpty($value)
    {
        return !empty($value);
    }

    /**
     * @return Rule
     */
    public static function notEmpty()
    {
        return new self(self::NOT_EMPTY);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private function assertUnique($value)
    {
        $haystack = $this->params['haystack'];
        if (is_callable($haystack)) {
            $retval = $haystack($value);
            if ($retval || $retval === 1 || $retval === true) {
                return false;
            }
            return true;
        } else {
            return !in_array($value, $haystack);
        }
    }

    /**
     * @param array|callable $haystack
     * @throws InvalidArgumentException
     * @return Rule
     */
    public static function unique($haystack)
    {
        if (!is_array($haystack) && !is_callable($haystack)) {
            throw new InvalidArgumentException;
        }
        return new self(self::UNIQUE, ['haystack' => $haystack]);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private function assertRegex($value)
    {
        $regex = $this->params['regex'];
        return (bool)preg_match($regex, $value);
    }

    /**
     * @param string $regex
     * @return Rule
     */
    public static function regex($regex)
    {
        return new self(self::REGEX, ['regex' => $regex]);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    private function assertInArray($value)
    {
        $array = $this->params['array'];
        return in_array($value, $array);
    }

    /**
     * @param string $array
     * @return Rule
     */
    public static function inArray($array)
    {
        return new self(self::IN_ARRAY, ['array' => $array]);
    }

    /**
     * @param mixed $value
     * @param array $relatedValues
     * @return bool
     */
    private function assertEqualsAttribute($value, array $relatedValues = null)
    {
        $attributeName = $this->params['attributeName'];

        if (isset($relatedValues[$attributeName]) && $value === $relatedValues[$attributeName]) {
            return true;
        }
        return false;
    }

    /**
     * @param string $attributeName
     * @return Rule
     */
    public static function equalsAttribute($attributeName)
    {
        return new self(self::EQUALS_ATTRIBUTE, ['attributeName' => $attributeName]);
    }
}
