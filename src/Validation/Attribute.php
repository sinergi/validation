<?php
namespace Sinergi\Validation;

class Attribute extends AbstractValidator
{
    /**
     * @var string
     */
    protected $attribute;

    /**
     * @var int
     */
    private $errorLimit;

    /**
     * @param string $attribute
     */
    public function __construct($attribute)
    {
        $this->setAttribute($attribute);
    }

    /**
     * @return int
     */
    public function getErrorLimit()
    {
        return $this->errorLimit;
    }

    /**
     * @param int $errorLimit
     * @return $this
     */
    public function setErrorLimit($errorLimit)
    {
        $this->errorLimit = $errorLimit;
        return $this;
    }


    /**
     * @param string $attribute
     * @return $this
     */
    public function setAttribute($attribute)
    {
        $this->attribute = $attribute;
        return $this;
    }

    /**
     * @return string
     */
    public function getAttribute()
    {
        return $this->attribute;
    }

    public function validate($value)
    {
    }
}