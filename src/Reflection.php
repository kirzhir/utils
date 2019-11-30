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
        $method = self::getProtectedMethod($object, $methodName);

        return $method->invokeArgs($object, $methodArgs);
    }

    /**
     * @param string $className
     * @param string|null $propertyName
     * @param null $propertyValue
     *
     * @return object
     *
     * @throws ReflectionException
     */
    public static function getObjectWithoutConstructor(string $className, string $propertyName = null, $propertyValue = null): object
    {
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
     * @param $propertyValue
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
        $reflectionObject = self::findPropertyClass(new ReflectionClass($object), $propertyName);

        $property = $reflectionObject->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * @param object $object
     * @param string $propertyName
     * @param $propertyValue
     *
     * @return object
     *
     * @throws ReflectionException
     */
    public static function setPrivateProperty(object $object, string $propertyName, $propertyValue)
    {
        $reflectionObject = self::findPropertyClass(new ReflectionClass($object), $propertyName);

        $property = $reflectionObject->getProperty($propertyName);
        $property->setAccessible(true);
        $property->setValue($object, $propertyValue);

        return $object;
    }

    /**
     * @param object $object
     * @param string $methodName
     *
     * @return ReflectionMethod
     *
     * @throws ReflectionException
     */
    private static function getProtectedMethod(object $object, string $methodName): ReflectionMethod
    {
        $method = new ReflectionMethod($object, $methodName);
        $method->setAccessible(true);

        return $method;
    }

    /**
     * @param ReflectionClass $reflectionObject
     * @param string $propertyName
     *
     * @return ReflectionClass
     *
     * @throws ReflectionException
     */
    private static function findPropertyClass(ReflectionClass $reflectionObject, string $propertyName): ReflectionClass
    {
        if ($reflectionObject->hasProperty($propertyName) === false) {
            $parentClass = $reflectionObject->getParentClass();
            if ($parentClass !== null) {
                $reflectionObject = self::findPropertyClass(new ReflectionClass($parentClass->name), $propertyName);
            }
        }

        return $reflectionObject;
    }
}
