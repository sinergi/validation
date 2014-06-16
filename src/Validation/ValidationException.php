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
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $errors
     * @return $this
     */
    public function setErrors($errors)
    {
        $this->errors = $errors;
        return $this;
    }
}