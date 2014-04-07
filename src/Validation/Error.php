<?php
namespace Sinergi\Validation;

class Error
{
    /**
     * @var string
     */
    private $error;

    /**
     * @param string $error
     */
    public function __construct($error)
    {
        $this->error = $error;
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