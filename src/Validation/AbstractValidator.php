<?php
namespace Sinergi\Validation;

abstract class AbstractValidator
{
    /**
     * @var string
     */
    protected $attribute;

    /**
     * @var array|Rule[]
     */
    protected $rules = [];

    /**
     * @var array|Rule[]
     */
    protected $errors = [];

    /**
     * @param Rule $rule
     * @return $this
     */
    public function addRule(Rule $rule)
    {
        $this->rules[] = $rule;
        return $this;
    }

    /**
     * @param array|Rule, ...
     * @return $this
     */
    public function addRules()
    {
        $rules = func_get_args();
        if (count($rules) === 1 && is_array($rules[0])) {
            $rules = $rules[0];
        }

        foreach ($rules as $rule) {
            $this->addRule($rule);
        }

        return $this;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function assert($value)
    {
        $retval = true;
        foreach ($this->rules as $rule) {
            $result = $rule->assert($value);
            if (!$result) {
                $retval = false;
                if (isset($this->attribute)) {
                    $this->errors[] = new Error($rule->getRule(), $this->attribute);
                } else {
                    $this->errors[] = new Error($rule->getRule());
                }
            }
        }
        return $retval;
    }

    /**
     * @return array|Error[]
     */
    public function errors()
    {
        return $this->errors;
    }
}