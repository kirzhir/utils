# Utils
Tools for development and unit testing

## Installation
```
composer require --dev kirillzhirov/utils
```

## Usage
``` php
\Utils\Reflection::invokeProtectedMethod($object, 'methodName', $arg1, $arg2);
```
``` php
\Utils\Reflection::getObjectWithoutConstructor($className);
\Utils\Reflection::getObjectWithoutConstructor($className, 'propertyName', 'propertyValue');
```
``` php
\Utils\Reflection::getProtectedProperty($object, 'propertyName');
```
``` php
\Utils\Reflection::setProtectedProperty($object, 'propertyName', 'propertyValue');
```
``` php
\Utils\Reflection::getPrivateProperty($object, 'propertyName');
```
``` php
\Utils\Reflection::setPrivateProperty($object, 'propertyName', 'propertyValue');
```
