<?php

namespace UtilsTest;

abstract class SomeAbstractClass {
    private $baz = 'baz';
    public function getBaz() {
        return $this->baz;
    }
}

class SomeClass {
    public function __construct($foo, $bar)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }

    private $foo;
    protected $bar;
}

use PHPUnit\Framework\TestCase;
use Utils\Reflection;

/**
 * Class ReflectionTest
 * @package UtilsTest
 *
 * @see Reflection
 */
class ReflectionTest extends TestCase
{
    /**
     * @see Reflection::invokeProtectedMethod()
     */
    public function testInvokeProtectedMethod()
    {
        $object = new class {
            protected function someProtectedFunction(): string
            {
                return 'foo';
            }
            private function somePrivateFunction(): string
            {
                return 'bar';
            }
            private function someFunctionWithArgs($foo, $bar): string
            {
                return $foo . $bar;
            }
        };

        $actual = Reflection::invokeProtectedMethod($object, 'someProtectedFunction');
        $this->assertEquals('foo', $actual);

        $actual = Reflection::invokeProtectedMethod($object, 'somePrivateFunction');
        $this->assertEquals('bar', $actual);

        $actual = Reflection::invokeProtectedMethod(
            $object, 'someFunctionWithArgs', 'foo', 'bar'
        );
        $this->assertEquals('foobar', $actual);

        $this->expectException(\ReflectionException::class);
        Reflection::invokeProtectedMethod($object, 'nonExistingMethod');
    }

    /**
     * @see Reflection::getObjectWithoutConstructor()
     */
    public function testGetObjectWithoutConstructor()
    {
        $actual = Reflection::getObjectWithoutConstructor(SomeClass::class);
        $expected = new SomeClass(null, null);
        $this->assertEquals($expected, $actual);

        $actual = Reflection::getObjectWithoutConstructor(
            SomeClass::class, 'foo', 'someProtectedValue'
        );
        $expected = new SomeClass('someProtectedValue', null);
        $this->assertEquals($expected, $actual);

        $actual = Reflection::getObjectWithoutConstructor(
            SomeClass::class, 'bar', 'somePrivateValue'
        );
        $expected = new SomeClass(null, 'somePrivateValue');
        $this->assertEquals($expected, $actual);

        $this->expectException(\ReflectionException::class);
        Reflection::getObjectWithoutConstructor(
            SomeClass::class, 'nonExistingProperty', 'someValue'
        );
    }

    /**
     * @see Reflection::getProtectedProperty()
     */
    public function testGetProtectedProperty()
    {
        $object = new SomeClass('foo', 'bar');

        $actual = Reflection::getProtectedProperty($object, 'foo');
        $this->assertEquals('foo', $actual);

        $actual = Reflection::getProtectedProperty($object, 'bar');
        $this->assertEquals('bar', $actual);

        $this->expectException(\ReflectionException::class);
        Reflection::getProtectedProperty($object, 'nonExistingProperty');
    }

    /**
     * @see Reflection::setProtectedProperty()
     */
    public function testSetProtectedProperty()
    {
        $object = new SomeClass(null, null);

        $actual = Reflection::setProtectedProperty($object, 'foo', 'foo');
        $expected = new SomeClass('foo', null);
        $this->assertEquals($expected, $actual);

        $actual = Reflection::setProtectedProperty($object, 'bar', 'bar');
        $expected = new SomeClass('foo', 'bar');
        $this->assertEquals($expected, $actual);

        $this->expectException(\ReflectionException::class);
        Reflection::setProtectedProperty($object, 'nonExistingProperty', 'bar');
    }

    /**
     * @see Reflection::getPrivateProperty()
     */
    public function testGetPrivateProperty()
    {
        $object = new class() extends SomeAbstractClass {};

        $actual = Reflection::getPrivateProperty($object, 'baz');
        $this->assertEquals('baz', $actual);

        $this->expectException(\ReflectionException::class);
        Reflection::getPrivateProperty($object, 'nonExistingProperty');
    }

    /**
     * @see Reflection::setPrivateProperty()
     */
    public function testSetPrivateProperty()
    {
        $object = new class extends SomeAbstractClass {};

        $actual = Reflection::setPrivateProperty($object, 'baz', 'foo');
        $this->assertEquals('foo', $actual->getBaz());

        $this->expectException(\ReflectionException::class);
        Reflection::setPrivateProperty($object, 'nonExistingProperty', 'foo');
    }
}
