<?php

namespace Utils;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;

/**
 * Class Reflection
 * @package Utils
 */
class Reflection
{
    /**
     * @param object $object
     * @param string $methodName
     * @param mixed ...$methodArgs
     *
     * @return mixed
     *
     * @throws ReflectionException
     */
    public static function invokeProtectedMethod(object $object, string $methodName, ...$methodArgs)
    {
        $method = new ReflectionMethod($object, $methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $methodArgs);
    }

    /**
     * @param string $className
     * @param string|null $propertyName
     * @param mixed $propertyValue
     *
     * @return object|null
     *
     * @throws ReflectionException
     */
    public static function getObjectWithoutConstructor(string $className, string $propertyName = null, $propertyValue = null): ?object
    {
        if (class_exists($className) === false) {
            return null;
        }

        $object = new ReflectionClass($className);
        $object = $object->newInstanceWithoutConstructor();

        if ($propertyName !== null) {
            self::setProtectedProperty($object, $propertyName, $propertyValue);
        }

        return $object;
    }

    /**
     * @param object $object
     * @param string $propertyName
     *
     * @return mixed
     *
     * @throws ReflectionException
     */
    public static function getProtectedProperty(object $object, string $propertyName)
    {
        $property = new ReflectionProperty($object, $propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * @param object $object
     * @param string $propertyName
     * @param mixed $propertyValue
     *
     * @return object
     *
     * @throws ReflectionException
     */
    public static function setProtectedProperty(object $object, string $propertyName, $propertyValue)
    {
        $property = new ReflectionProperty($object, $propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $propertyValue);

        return $object;
    }

    /**
     * @param object $object
     * @param string $propertyName
     *
     * @return mixed
     *
     * @throws ReflectionException
     */
    public static function getPrivateProperty(object $object, string $propertyName)
    {
        $reflectionObject = self::propertyOwnerSearch(new ReflectionClass($object), $propertyName);

        $property = $reflectionObject->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * @param object $object
     * @param string $propertyName
     * @param mixed $propertyValue
     *
     * @return object
     *
     * @throws ReflectionException
     */
    public static function setPrivateProperty(object $object, string $propertyName, $propertyValue)
    {
        $reflectionObject = self::propertyOwnerSearch(new ReflectionClass($object), $propertyName);

        $property = $reflectionObject->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $propertyValue);

        return $object;
    }

    /**
     * @param ReflectionClass $reflectionObject
     * @param string $propertyName
     *
     * @return ReflectionClass
     *
     * @throws ReflectionException
     */
    private static function propertyOwnerSearch(ReflectionClass $reflectionObject, string $propertyName): ReflectionClass
    {
        if ($reflectionObject->hasProperty($propertyName) === false) {
            $parentClass = $reflectionObject->getParentClass();
            if ($parentClass !== false && class_exists($parentClass->name) !== false) {
                $reflectionObject = self::propertyOwnerSearch(new ReflectionClass($parentClass->name), $propertyName);
            }
        }

        return $reflectionObject;
    }
}
