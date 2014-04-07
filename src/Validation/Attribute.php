<?php
namespace Sinergi\Validation;

class Attribute extends AbstractValidator
{
    /**
     * @var string
     */
    private $attribute;

    /**
     * @param string $attribute
     */
    public function __construct($attribute)
    {
        $this->setAttribute($attribute);
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