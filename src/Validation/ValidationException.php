<?php
namespace Sinergi\Validation;

use Exception;

class ValidationException extends Exception
{
    /**
     * @var array|Error[]
     */
    private $errors;

    /**
     * @return array|Error[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array|Error[] $errors
     * @return $this
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
        return $this;
    }
}