# Utils
Is a small helper package to ease testing with PHPUnit.
 - Creating a mock object with disabled constructor and manually setted protected/private properties;
 - Testing protected/private methods;
 - Testing protected/private properties.

## Installation
```
composer require --dev kirillzhirov/utils
```

## Usage
``` php
class Foo {
    private $bar;

    public function __construct($bar)
    {
        $this->bar = $bar + 1;
    }

    private function baz($foo, $bar)
    {
        $this->bar = $this->bar * $foo - $bar;
    }
}

use PHPUnit\Framework\TestCase;
use Utils\Reflection;

class FooTest extends TestCase
{
    public function testBaz()
    {
        $foo = Reflection::getObjectWithoutConstructor(Foo::class, 'bar', 10);
        Reflection::invokeProtectedMethod($foo, 'baz', 2, 5);
        $actual = Reflection::getProtectedProperty($foo, 'bar');
        $this->assertEquals(15, $actual);
    }
}
```
