<?php
namespace Sinergi\Validation;

use ReflectionClass;
use ReflectionProperty;
use ReflectionMethod;

class Validator extends AbstractValidator
{
    /**
     * @var array
     */
    private static $reflections = [];

    /**
     * @var array|Attribute[]
     */
    protected $attributes = [];

    /**
     * @param string $attribute
     * @return Attribute
     */
    public function addAttribute($attribute)
    {
        return $this->attributes[] = new Attribute($attribute);
    }

    /**
     * @param mixed $value
     * @param array|null $mask
     * @return bool
     * @throws ValidationException
     */
    public function validate($value, array $mask = null)
    {
        $retval = true;
        if (count($this->attributes)) {
            foreach ($this->attributes as $attribute) {
                if (null === $mask || in_array($attribute->getAttribute(), $mask)) {
                    $attributeValue = $this->getAttributeValue($value, $attribute->getAttribute());
                    $assertion = $attribute->assert($attributeValue);
                    if (!$assertion) {
                        $retval = false;
                        $this->errors = array_merge($this->errors, array_slice($attribute->errors(), 0, $attribute->getErrorLimit()));
                    }
                }
            }
        } else {
            $retval = $this->assert($value);
        }
        if (!$retval) {
            $validationException = new ValidationException();
            $validationException->setErrors($this->errors);
            throw $validationException;
        }
        return $retval;
    }

    /**
     * @param array|object $object
     * @param string $attribute
     * @return mixed
     */
    private function getAttributeValue($object, $attribute)
    {
        if (is_array($object) && isset($object[$attribute])) {
            return $object[$attribute];
        } elseif (is_object($object)) {
            $gettersPrefix = ['get', 'is', 'has'];
            $getters = [$attribute, str_replace('_', '', $attribute)];
            foreach ($gettersPrefix as $prefix) {
                $getters[] = strtolower($prefix . $attribute);
                $getters[] = strtolower($prefix . '_' . $attribute);
                $getters[] = strtolower($prefix . str_replace('_', '', $attribute));
            }

            list($properties, $methods) = $this->getReflection($object);

            foreach ($getters as $getter) {
                if (isset($properties[$getter])) {
                    return $object->{$properties[$getter]};
                } elseif (isset($methods[$getter])) {
                    return call_user_func([$object, $methods[$getter]]);
                }
            }
        }
        return null;
    }

    /**
     * @param object $object
     * @return array
     */
    private function getReflection($object)
    {
        $className = get_class($object);
        if (isset(self::$reflections[$className])) {
            return self::$reflections[$className];
        }

        $reflect = new ReflectionClass($className);

        $properties = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        $properties = array_map(function (ReflectionProperty $value) { return $value->name; }, $properties);
        $propertiesKeys = array_map(function ($value) { return strtolower($value); }, $properties);
        $properties = array_combine($propertiesKeys, $properties);

        $methods = $reflect->getMethods(ReflectionMethod::IS_PUBLIC);
        $methods = array_map(function (ReflectionMethod $value) { return $value->name; }, $methods);
        $methodsKeys = array_map(function ($value) { return strtolower($value); }, $methods);
        $methods = array_combine($methodsKeys, $methods);

        return self::$reflections[$className] = [$properties, $methods];
    }
}