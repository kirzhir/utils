# Utils
Tools for development and unit testing

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
