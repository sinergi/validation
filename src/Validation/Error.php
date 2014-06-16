<?php
namespace Sinergi\Validation;

class Error
{
    /**
     * @var string
     */
    private $error;

    /**
     * @var string
     */
    private $attribute;

    /**
     * @param string $error
     * @param null|string $attribute
     */
    public function __construct($error, $attribute = null)
    {
        $this->error = $error;
        if (null !== $attribute) {
            $this->setAttribute($attribute);
        }
    }

    /**
     * @return string
     */
    public function getAttribute()
    {
        return $this->attribute;
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
     * @param string $error
     * @return $this
     */
    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->error;
    }
}